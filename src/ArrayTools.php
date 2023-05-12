<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 11:02 AM -- May 12th, 2023.
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

namespace T99\Util;

use InvalidArgumentException;

/**
 * Provides functions for working with arrays.
 */
class ArrayTools {
	
	/**
	 * Returns a flattened version of the input array, up to the specified depth
	 * (default: 1).
	 *
	 * @param array $array The array to flatten.
	 * @param float $max_depth The maximum depth to flatten the array to.
	 * @return array The flattened array.
	 * @throws InvalidArgumentException If $depth is a negative integer.
	 */
	public static function flatten(
		array $array,
		float $max_depth = INF,
		// TODO -- Add a $key_strategy parameter.
	): array {
		
		if ($max_depth === 0.0) return $array;
		else if ($max_depth < 0) {
			
			throw new InvalidArgumentException(
				"\$depth must be a non-negative integer.",
			);
			
		}
		
		$result = [];
		
		foreach ($array as $key => $value) {
			
			if (!is_array($value)) $result[$key] = $value;
			else {
				
				$result = array_merge(
					$result,
					self::flatten($value, $max_depth - 1),
				);
				
			}
			
		}
		
		return $result;
		
	}
	
	/**
	 * Returns true if every element from the input array satisfies the
	 * condition defined by the specified callback.
	 * 
	 * If no callback is provided, the default callback checks if the value is
	 * truthy.
	 * 
	 * The callback should take the following form:
	 * 
	 * ```php
	 * fn(int | string $key, mixed $value): bool => ...
	 * ```
	 * 
	 * @param callable | null $callback The callback function to check each
	 * element of the input array against. This callback should accept two
	 * parameters: the key and the value, and return a boolean indicating
	 * whether or not that key-value pair satisfies the condition. If not
	 * provided, the default callback checks if the value is truthy.
	 * @param array ...$arrays The input arrays to check.
	 * @return bool true if every element from the input array satisfies the
	 * condition defined by the specified callback.
	 * @test \T99\Util\Tests\Arrays\array_all_test
	 * @see ArrayTools::all
	 * @see ArrayTools::none
	 */
	function any(
		callable | null $callback = null,
		array ...$arrays,
	): bool {
		
		// $callback ??= fn(int | string $key, mixed $value): bool => !!$value;
		// $largest_input_array_length = array_largest_countable($arrays);
		// $arrays = array_map(
		// 	callback: fn(array $array): array =>
		// 		array_pad($array, $largest_input_array_length, null),
		// 	array: $arrays,
		// );
		//
		// foreach (array_simul_iterate_entries(...$arrays) as $entries) {
		//	
		// 	[$key, $value] = $entries;
		//	
		// 	if ($callback(array_fla)) return true;
		//	
		// }
		//
		// foreach ($array as $key => $value) {
		//	
		// 	if ($callback($value, $key)) return true;
		//	
		// }
		
		return false;
		
	}
	
	/**
	 * Returns true if every element from the input array satisfies the
	 * condition defined by the specified callback.
	 *
	 * If no callback is provided, the default callback checks if every element
	 * from the input array is truthy.
	 *
	 * The callback should take the following form:
	 *
	 * ```php
	 * fn(int | string $key, mixed $value): bool => ...
	 * ```
	 *
	 * @param array $array The array to check.
	 * @param callable | null $callback The callback function to check each
	 * element of the input array against. This callback should accept two
	 * parameters: the key and the value, and return a boolean indicating
	 * whether or not that key-value pair satisfies the condition. If not
	 * provided, the default callback checks if the value is truthy.
	 * @return bool true if every element from the input array satisfies the
	 * condition defined by the specified callback.
	 * @see ArrayTools::any
	 * @see ArrayTools::none
	 */
	public static function all(
		array $array,
		callable | null $callback = null,
	): bool {
		
		$callback ??= fn(int | string $key, mixed $value): bool => !!$value;
		
		foreach ($array as $key => $value) {
			
			if (!$callback($key, $value)) return false;
			
		}
		
		return true;
		
	}
	
	/**
	 * Returns true if no element from the input array satisfies the condition
	 * defined by the specified callback.
	 * 
	 * If no callback is provided, the default callback checks if the value is
	 * truthy.
	 * 
	 * The callback should take the following form:
	 * 
	 * ```php
	 * fn(int | string $key, mixed $value): bool => ...
	 * ```
	 * 
	 * @param array $array The array to check.
	 * @param callable | null $callback The callback function to check each
	 * element of the input array against. This callback should accept two
	 * parameters: the key and the value, and return a boolean indicating
	 * whether or not that key-value pair satisfies the condition. If not
	 * provided, the default callback checks if the value is truthy.
	 * @return bool true if no element from the input array satisfies the
	 * condition defined by the specified callback.
	 * @see ArrayTools::any
	 * @see ArrayTools::all
	 */
	function none(
		array $array,
		callable | null $callback = null,
	): bool {
		
		$callback ??= fn(int | string $key, mixed $value): bool => !!$value;
		
		foreach ($array as $key => $value) {
			
			if ($callback($value, $key)) return true;
			
		}
		
		return false;
		
	}
	
	/**
	 * Returns an array of key-value pairs for the input array.
	 *
	 * @param array $array The associative array to extract key-value pairs
	 * from.
	 * @return array An array of arrays, wherein each sub-array is a key-value
	 * pair/tuple/entry from the input array.
	 */
	function entries(array $array): array {
		
		return array_map(
			callback: fn($key): array => [$key, $array[$key]],
			array: array_keys($array),
		);
		
	}
	
	/**
	 * Returns the first $count elements of the given array, or all but the last
	 * $count elements if $count is negative.
	 *
	 * @param array $array The array to get leading elements from.
	 * @param int $count The number of leading elements to retrieve, or the
	 * number of trailing elements to exclude if negative.
	 * @param bool $preserve_keys By default, the returned array will have been
	 * re-indexed. If this parameter is set to true, the array keys will be
	 * preserved.
	 * @return array The first $count elements of the given array, or all but
	 * the last $count elements if $count is negative.
	 */
	function head(
		array $array,
		int $count = 1,
		bool $preserve_keys = false,
	): array {
		
		return array_slice(
			array: $array,
			offset: 0,
			length: $count,
			preserve_keys: $preserve_keys,
		);
		
	}
	
	/**
	 * Returns the last $count elements of the given array, or all but the first
	 * $count elements if $count is negative.
	 * 
	 * @param array $array The array to get trailing elements from.
	 * @param int $count The number of trailing elements to retrieve, or the
	 * number of leading elements to exclude if negative.
	 * @param bool $preserve_keys By default, the returned array will have been
	 * re-indexed. If this parameter is set to true, the array keys will be
	 * preserved.
	 * @return array The last $count elements of the given array, or all but the
	 * first $count elements if $count is negative. 
	 */
	function tail(
		array $array,
		int $count = 1,
		bool $preserve_keys = false,
	): array {
		
		return array_slice(
			array: $array,
			offset: $count <= 0 ? abs($count) : -$count,
			length: null,
			preserve_keys: $preserve_keys,
		);
		
	}
	
	/**
	 * Returns the nth (default: 1) key-value pair from the input array for
	 * which the callback function returns true. If $n is negative, the nth
	 * key-value pair from the end of the array is returned. If no key-value
	 * pair satisfies the condition, the value provided for the $fallback
	 * parameter is returned (default: null).
	 *
	 * The callback should take the following form:
	 *
	 * ```php
	 * fn(int | string $key, mixed $value): bool => ...
	 * ```
	 *
	 * @param array $array The array to search.
	 * @param callable $callback The callback function to use. This callback
	 * should accept two parameters: the key and the value, and return a boolean
	 * indicating whether or not that key-value pair satisfies the condition.
	 * @param int $n The nth key-value pair to return. If $n is positive, the
	 * nth key-value pair from the beginning of the array is returned. If $n is
	 * negative, the nth key-value pair from the end of the array is returned.
	 * @param mixed $fallback The value to return if no key-value pair satisfies
	 * the condition (default: null).
	 * @return array | mixed The nth (default: 1) key-value pair from the input
	 * array for which the callback function returns true. If $n is negative,
	 * the nth key-value pair from the end of the array is returned. If no
	 * key-value pair satisfies the condition, the value provided for the
	 * $fallback parameter is returned (default: null).
	 * @throws InvalidArgumentException If $n is zero.
	 */
	function find_entry(
		array $array,
		callable $callback,
		int $n = 1,
		mixed $fallback = null,
	): mixed {
		
		if ($n === 0) {
			
			throw new InvalidArgumentException(
				"The value of $n must be greater than, or less than, zero.",
			);
			
		}
		
		$callback ??= fn(int | string $key, mixed $value): bool => !!$value;
		$iterable = $n > 0 ? $array : self::reverse_iterator($array);
		$n = abs($n);
		
		foreach ($iterable as $key => $value) {
			
			if ($callback($key, $value)) {
				
				if ($n <= 1) return [$key, $value];
				else $n--;
				
			}
			
		}
		
		return $fallback;
		
	}
	
	/**
	 * Returns the value from the nth (default: 1) key-value pair from the input
	 * array for which the callback function returns true. If $n is negative,
	 * the value from the nth key-value pair from the end of the array is
	 * returned. If no key-value pair satisfies the condition, the value
	 * provided for the $fallback parameter is returned (default: null).
	 *
	 * The callback should take the following form:
	 *
	 * ```php
	 * fn(int | string $key, mixed $value): bool => ...
	 * ```
	 *
	 * @param array $array The array to search.
	 * @param callable $callback The callback function to use. This callback
	 * should accept two parameters: the key and the value, and return a boolean
	 * indicating whether or not that key-value pair satisfies the condition.
	 * @param int $n The nth value to return. If $n is positive, the nth value
	 * from the beginning of the array is returned. If $n is negative, the nth
	 * value from the end of the array is returned.
	 * @param mixed $fallback The value to return if no key-value pair satisfies
	 * the condition (default: null).
	 * @return int | string | null The value from the nth (default: 1) key-value
	 * pair from the input array for which the callback function returns true.
	 * If $n is negative, the value from the nth key-value pair from the end of
	 * the array is returned. If no key-value pair satisfies the condition, the
	 * value provided for the $fallback parameter is returned (default: null).
	 * @throws InvalidArgumentException If $n is zero.
	 */
	function find(
		array $array,
		callable $callback,
		int $n = 1,
		mixed $fallback = null,
	): mixed {
		
		return self::find_entry($array, $callback, $n)[1] ?? $fallback;
		
	}
	
	/**
	 * Returns the key from the nth (default: 1) key-value pair from the input
	 * array for which the callback function returns true. If $n is negative,
	 * the key from the nth key-value pair from the end of the array is
	 * returned. If no key-value pair satisfies the condition, the value
	 * provided for the $fallback parameter is returned (default: null).
	 *
	 * The callback should take the following form:
	 *
	 * ```php
	 * fn(int | string $key, mixed $value): bool => ...
	 * ```
	 *
	 * @param array $array The array to search.
	 * @param callable $callback The callback function to use. This callback
	 * should accept two parameters: the key and the value, and return a boolean
	 * indicating whether or not that key-value pair satisfies the condition.
	 * @param int $n The nth key to return. If $n is positive, the nth key from
	 * the beginning of the array is returned. If $n is negative, the nth key
	 * from the end of the array is returned.
	 * @param mixed $fallback The value to return if no key-value pair satisfies
	 * the condition (default: null).
	 * @return int | string | mixed The key from the nth (default: 1) key-value
	 * pair from the input array for which the callback function returns true.
	 * If $n is negative, the key from the nth key-value pair from the end of
	 * the array is returned. If no key-value pair satisfies the condition, the
	 * value provided for the $fallback parameter is returned (default: null).
	 * @throws InvalidArgumentException If $n is zero.
	 */
	function find_key(
		array $array,
		callable $callback,
		int $n = 1,
		mixed $fallback = null,
	): mixed {
		
		return self::find_entry($array, $callback, $n)[0] ?? $fallback;
		
	}
	
	/**
	 * Given a 2-dimensional array, this function returns a mapped 2-dimensional
	 * array wherein the rows of the input array have been 'grouped'.
	 * 
	 * 'Grouping' involves merging similar rows of the input array. Rows are
	 * considered 'similar' if some subset of their fields are found to be
	 * equivalent to each other. This subset of fields is identified using the
	 * '$group_by' argument. 'Merging' involves the creation of an array of the
	 * values of similar rows, wherein this array of similar rows is substituted in
	 * as the value of the field from which the array was created for the 'grouped'
	 * row. The fields that will be merged can be decided using the '$group_into'
	 * argument.
	 * 
	 * Effectively, this function serves a very similar purpose as the SQL
	 * 'GROUP BY' statement.
	 * 
	 * @param array $input The input 2-dimensional array to group.
	 * @param array $group_by The fields that will be used to decide if two rows
	 * should be merged.
	 * @param array $group_into The fields that will be merged for rows determined
	 * to be 'similar'.
	 * @return array The resulting grouped array.
	 */
	function group(array $input, array $group_by, array $group_into): array {
		
		$results = [];
		$current_item = null;
		$current_group = array_fill(0, count($group_by), "");
		
		foreach ($input as $item) {
			
			$is_item_object = is_object($item);
			
			$item = (array) $item;
			
			$item_group = array_map(
				callback: fn($group_by_field) => $item[$group_by_field],
				array: $group_by,
			);
			
			if ($item_group !== $current_group) {
				
				if ($current_item !== null) {
					
					if ($is_item_object) $results[] = (object) $current_item;
					else $results[] = $current_item;
					
				}
				
				$current_group = $item_group;
				$current_item = $item;
				
				foreach ($group_into as $group_into_field) {
					
					$current_item[$group_into_field] = [];
				
				}
				
			}
			
			foreach ($group_into as $group_into_field) {
				
				$current_item[$group_into_field][] = $item[$group_into_field];
				
			}
			
		}
		
		if ($current_item !== null) {
			
			if ($is_item_object) $results[] = (object) $current_item;
			else $results[] = $current_item;
			
		}
		
		return $results;
		
	}
	
	/**
	 * Returns an iterable that iterates over the input array in reverse order.
	 * 
	 * This is often useful when array_reverse is prohibitively expensive, such
	 * as when the array is very large and/or when the array is not a simple
	 * array.
	 * 
	 * @param array $array The array to iterate over in reverse order.
	 * @return iterable An iterable that iterates over the input array in
	 * reverse order.
	 */
	function reverse_iterator(array $array): iterable {
		
		for (end($array); ($key = key($array)) !== null; prev($array)) {
			
			$value = current($array);
			
			yield $key => $value;
			
		}
		
	}
	
	/**
	 * Surrounds each element of the input array with the specified string(s).
	 *
	 * @param array $array The array of values to surround.
	 * @param string $string The string to prepend to the beginning of each
	 * value in the input array. If no third argument is provided, this string is
	 * also appended to the end of each value in the input array. 
	 * @param string | null $trailing_string An optional override for the string
	 * to use to append to the end of each value in the input array. If this
	 * argument is not provided, it will default to using the same string as the
	 * previous parameter.
	 * @return array A new array with each value from the input array having been
	 * surrounded by the specified strings.
	 */
	function surround(
		array $array,
		string $string,
		string $trailing_string = null,
	): array {
		
		$trailing_string ??= $string;
		
		return array_map(
			callback: fn(mixed $item): string =>
				"${string}${item}${trailing_string}",
			array: $array,
		);
		
	}
	
}
