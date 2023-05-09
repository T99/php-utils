<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 2:43 PM -- March 14th, 2023.
 * Project: php-utils
 * 
 * php-utils
 * Copyright (C) 2023 Trevor Sears
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace T99\Util\Structures;

use Countable;
use Exception;
use SplFixedArray;

/**
 * A fixed-size buffer/array that behaves as if it were connected end-to-end.
 * 
 * Example:
 * ```php
 * $circular_buffer = new CircularBuffer(4);
 * $circular_buffer->push(1, 2, 3, 4);
 * 
 * echo join(", ", $circular_buffer->toArray()); // [1, 2, 3, 4]
 * 
 * $circular_buffer->push(5);
 * 
 * echo join(", ", $circular_buffer->toArray()); // [2, 3, 4, 5]
 * 
 * $circular_buffer->push(6, 7);
 * 
 * echo join(", ", $circular_buffer->toArray()); // [4, 5, 6, 7]
 * 
 * echo $circular_buffer->pop(); // 7
 * ```
 */
class CircularBuffer implements Countable {
	
	/**
	 * @var int The index of the first element in the internal buffer.
	 */
	protected int $first_index;
	
	/**
	 * @var int A count of the current number of elements contained in this
	 * CircularBuffer.
	 */
	protected int $size;
	
	/**
	 * @var SplFixedArray An internal fixed-size array that is used to maintain
	 * the outward-facing circular buffer structure.
	 */
	protected SplFixedArray $buffer;
	
	/**
	 * @var bool Set to true to allow methods that insert data into this
	 * CircularBuffer to overwrite existing data. Otherwise, these methods will
	 * throw exceptions if more data is inserted than there is space for.
	 */
	protected bool $allow_overwrite;
	
	/**
	 * @var bool Set to true to allow methods that remove/take data out of this
	 * CircularBuffer to return null rather than throwing an exception if a
	 * remove/take operation should occur while this CircularBuffer is already
	 * empty.
	 */
	protected bool $null_on_overremove;
	
	/**
	 * Initializes a new CircularBuffer with the specified capacity and options.
	 * 
	 * @param int $capacity The maximum number of elements that this
	 * CircularBuffer should be capable of holding.
	 * @param bool $allow_overwrite Set to true to allow methods that insert
	 * data into this CircularBuffer to overwrite existing data. Otherwise,
	 * these methods will throw exceptions if more data is inserted than there
	 * is space for. Defaults to false.
	 * @param bool $null_on_overremove Set to true to allow methods that
	 * remove/take data out of this CircularBuffer to return null rather than
	 * throwing an exception if a remove/take operation should occur while this
	 * CircularBuffer is already empty. Defaults to true.
	 */
	public function __construct(
		int $capacity,
		bool $allow_overwrite = false,
		bool $null_on_overremove = true,
	) {
		
		$this->first_index = 0;
		$this->size = 0;
		$this->buffer = new SplFixedArray($capacity);
		$this->allow_overwrite = $allow_overwrite;
		$this->null_on_overremove = $null_on_overremove;
		
	}
	
	/**
	 * Wraps/normalizes the provided index to be within the interval
	 * (0, capacity] (i.e. 0 <= index < capacity).
	 * 
	 * @param int $index The index to wrap/normalize.
	 * @return int A wrapped/normalized version of the provided index, occurring
	 * on the interval (0, capacity] (i.e. 0 <= index < capacity).
	 */
	protected function wrapIndex(int $index): int {
		
		$capacity = $this->getCapacity();
		
		return (($index % $capacity) + $capacity) % $capacity;
		
	}
	
	/**
	 * Checks that the provided insertion size can be supported by the current
	 * state of the internal data structures.
	 * 
	 * @param int $insertion_size The theoretical number of elements being
	 * inserted.
	 * @throws Exception An exception thrown if the specified insertion size
	 * cannot be supported by the current state of the internal data structures.
	 */
	protected function checkInsertionLimitations(int $insertion_size): void {
		
		if (!$this->allow_overwrite && ($insertion_size > $this->freeSpace())) {
			
			throw new Exception(
				"Attempted to insert more elements into a CircularBuffer " .
				"than the structure had space for! Either ensure that the " .
				"number of inserted rows does not exceed " .
				"\$cirular_buffer->freeSpace() or enable \$allow_overwrite " .
				"when initializing your CircularBuffer to allow for " .
				"automatically overwriting elements rather than showing " .
				"this message."
			);
			
		}
		
	}
	
	/**
	 * Checks that the provided removal size can be supported by the current
	 * state of the internal data structures.
	 *
	 * @param int $removal_size The theoretical number of elements being
	 * removed/taken.
	 * @return void
	 * @throws Exception An exception thrown if the specified removal size
	 * cannot be supported by the current state of the internal data structures.
	 */
	protected function checkRemovalLimitations(int $removal_size): void {
		
		if (!$this->null_on_overremove && ($removal_size > count($this))) {
			
			throw new Exception(
				"Attempted to remove/take more elements from a " .
				"CircularBuffer than the structure contained! Either ensure " .
				"that the number of rows to be removed/taken does not exceed " .
				"count(\$circular_buffer) or enable \$null_on_overremove " .
				"when initializing your CircularBuffer to allow for instead " .
				"returning null from methods that would otherwise throw this " .
				"error on 'over-removal'."
			);
			
		}
		
	}
	
	/**
	 * Returns true if the index of the first element occurs later than the
	 * index of the last element in the internal array.
	 * 
	 * This additionally indicates that the populated portion of the internal
	 * array spans over the 'break' in the internal array.
	 * 
	 * @return bool true if the index of the first element occurs later than the
	 * index of the last element in the internal array.
	 */
	protected function arePointersReversed(): bool {
		
		return $this->first_index > $this->wrapIndex($this->getEndIndex() - 1);
		
	}
	
	/**
	 * Returns the index of the last element in the internal buffer.
	 * 
	 * @return int The index of the last element in the internal buffer.
	 */
	protected function getEndIndex(): int {
		
		return $this->wrapIndex($this->first_index + $this->size);
		
	}
	
	/**
	 * Returns the capacity of this CircularBuffer.
	 * 
	 * @return int The capacity of this CircularBuffer.
	 */
	public function getCapacity(): int {
		
		return $this->buffer->getSize();
		
	}
	
	/**
	 * Returns a count of the total number of elements occupying this
	 * CircularBuffer.
	 * 
	 * @return int A count of the total number of elements occupying this
	 * CircularBuffer.
	 */
	public function count(): int {
		
		return $this->size;
		
	}
	
	/**
	 * Returns true if this CircularBuffer is empty.
	 * 
	 * @return bool true if this CircularBuffer is empty.
	 */
	public function isEmpty(): bool {
		
		return count($this) === 0;
		
	}
	
	/**
	 * Returns true if this CircularBuffer is full.
	 *
	 * @return bool true if this CircularBuffer is full.
	 */
	public function isFull(): bool {
		
		return count($this) === $this->getCapacity();
		
	}
	
	/**
	 * Returns an integer count of the number of 'free spaces' occurring within
	 * this CircularBuffer.
	 * 
	 * @return int An integer count of the number of 'free spaces' occurring
	 * within this CircularBuffer.
	 */
	public function freeSpace(): int {
		
		return $this->getCapacity() - count($this);
		
	}
	
	/**
	 * Inserts the provided elements at the beginning of this CircularBuffer,
	 * and returns the new size of this CircularBuffer.
	 * 
	 * @param array ...$elements A list of elements to enqueue into this
	 * CircularBuffer.
	 * @return int The new size of this CircularBuffer.
	 * @throws Exception An exception thrown if the specified insertion size
	 * cannot be supported by the current state of the internal data structures.
	 */
	public function enqueue(mixed ...$elements): int {
		
		$elements = array_slice($elements, -($this->getCapacity()));
		
		$this->checkInsertionLimitations(count($elements));
		
		foreach ($elements as $element) {
			
			$was_full = $this->isFull();
			
			$new_first_index = $this->wrapIndex($this->first_index - 1);
			$this->buffer[$new_first_index] = $element;
			$this->first_index = $new_first_index;
			
			if (!$was_full) $this->size++;
			// else $this->first_index = $this->wrapIndex($this->first_index - 1);
			
		}
		
		return count($this);
		
	}
	
	/**
	 * Inserts the provided elements at the end of this CircularBuffer, and
	 * returns the new size of this CircularBuffer.
	 * 
	 * @param mixed ...$elements
	 * @return int
	 * @throws Exception An exception thrown if the specified insertion size
	 * cannot be supported by the current state of the internal data structures.
	 */
	public function push(mixed ...$elements): int {
		
		$elements = array_slice($elements, -($this->getCapacity()));
		
		$this->checkInsertionLimitations(count($elements));
		
		foreach ($elements as $element) {
			
			$was_full = $this->isFull();
			
			$this->buffer[$this->getEndIndex()] = $element;
			
			if (!$was_full) $this->size++;
			else $this->first_index = $this->wrapIndex($this->first_index + 1);
			
		}
		
		return count($this);
		
	}
	
	/**
	 * Removes and returns the first element in this CircularBuffer.
	 * 
	 * @return mixed The first element in this CircularBuffer, having been
	 * removed from the CircularBuffer.
	 * @throws Exception An exception thrown if there are no elements in this
	 * CircularBuffer to remove and return, and $null_on_overremove is false.
	 */
	public function shift(): mixed {
		
		if ($this->isEmpty() && $this->null_on_overremove) return null;
		else $this->checkRemovalLimitations(1);
		
		$result = $this->buffer[$this->first_index];
		$this->buffer[$this->first_index] = null;
		$this->first_index = $this->wrapIndex($this->first_index + 1);
		$this->size--;
		
		return $result;
		
	}
	
	/**
	 * Removes and returns the last element in this CircularBuffer.
	 *
	 * @return mixed The last element in this CircularBuffer, having been
	 * removed from the CircularBuffer.
	 * @throws Exception An exception thrown if there are no elements in this
	 * CircularBuffer to remove and return, and $null_on_overremove is false.
	 */
	public function dequeue(): mixed {
		
		if ($this->isEmpty() && $this->null_on_overremove) return null;
		else $this->checkRemovalLimitations(1);
		
		$result_index = $this->wrapIndex($this->getEndIndex() - 1);
		$result = $this->buffer[$result_index];
		$this->buffer[$result_index] = null;
		$this->size--;
		
		return $result;
		
	}
	
	/**
	 * Alias for {@link CircularBuffer#dequeue}.
	 */
	public function pop(): mixed {
		
		return $this->dequeue();
		
	}
	
	/**
	 * Converts the contents of this CircularBuffer into an array and returns
	 * the result.
	 * 
	 * @return array The contents of this CircularBuffer, having been converted
	 * to an array.
	 */
	public function toArray(): array {
		
		if ($this->isEmpty()) return [];
		
		$array = $this->buffer->toArray();
		
		if ($this->arePointersReversed()) {
			
			return [
				...array_slice(
					array: $array,
					offset: $this->first_index
				),
				...array_slice(
					array: $array,
					offset: 0,
					length: $this->getEndIndex(),
				),
			];
			
		} else {
			
			return array_slice(
				array: $array,
				offset: $this->first_index,
				length: count($this),
			);
			
		}
		
	}
	
}
