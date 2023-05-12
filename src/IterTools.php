<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 11:01 AM -- May 12th, 2023.
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

use function T99\Util\Formatting\var_dump_to_string;

/**
 * Provides functions for working with iterables.
 */
class IterTools {
	
	/**
	 * Returns true if all of the provided iterables are valid.
	 *
	 * @param iterable ...$iterables The iterables to check.
	 * @return bool true if all of the provided iterables are valid.
	 */
	public static function all_are_valid(iterable ...$iterables): bool {
		
		foreach ($iterables as $iterable) {
			
			if (!$iterable->valid()) return false;
			
		}
		
		return true;
		
	}
	
	/**
	 * Returns true if at least one of the provided iterables is valid.
	 *
	 * @param iterable ...$iterables The iterables to check.
	 * @return bool true if at least one of the provided iterables is valid.
	 */
	public static function some_are_valid(iterable ...$iterables): bool {
		
		foreach ($iterables as $iterable) {
			
			if ($iterable->valid()) return true;
			
		}
		
		return false;
		
	}
	
	/**
	 * Returns an iterable that yields the entries of the provided iterables,
	 * zipped together.
	 *
	 * ```php
	 * $iterables = [
	 *     [1, 2, 3],
	 *     [4, 5, 6],
	 *     [7, 8, 9],
	 * ];
	 *
	 * foreach (iter_zip_entries($iterables) as $entry) {
	 *     [$key, $value] = $entry;
	 *     echo "$key => $value\n";
	 * }
	 *
	 * // Output:
	 * // 0 => 1
	 * // 1 => 4
	 * // 2 => 7
	 * ```
	 *
	 * @param array $iterables
	 * @param string $zip_mode
	 * @param mixed|null $fill_value
	 * @return iterable
	 */
	public static function zip_entries(
		array $iterables,
		string $zip_mode = "shortest",
		mixed $fill_value = null,
	): iterable {
		
		$continuation_check = match ($zip_mode) {
			
			"shortest" => fn(array $iterables): bool =>
				self::all_are_valid(...$iterables),
			
			"longest" => fn(array $iterables): bool =>
				self::some_are_valid(...$iterables),
			
			default => throw new \InvalidArgumentException(
				"Invalid strategy: $zip_mode. Valid strategies are 'shortest' " .
				"and 'longest'."
			),
			
		};
		
		while ($continuation_check(...$iterables)) {
			
			yield array_map(
				callback: fn(iterable $iterable): array => $iterable->valid() ?
					[$iterable->key(), $iterable->current()] : $fill_value,
				array: $iterables,
			);
			
			foreach ($iterables as $iterable) $iterable->next();
			
		}
		
	}
	
	public static function map(
		callable $callback,
		array $iterables,
		string $zip_mode = "shortest",
		mixed $fill_value = null,
	): iterable {
		
		$iterator = self::zip_entries($iterables, $zip_mode, $fill_value);
		
		foreach ($iterator as $entry) {
			
			yield $callback(...ArrayTools::flatten($entry));
			
		}
		
	}
	
	public static function map_assoc(
		callable $callback,
		array $iterables,
		string $zip_mode = "shortest",
		mixed $fill_value = null,
		bool $allow_key_type_coercion = false,
	): iterable {
		
		$iterator = self::zip_entries($iterables, $zip_mode, $fill_value);
		
		foreach ($iterator as $entry) {
			
			$input = ArrayTools::flatten($entry);
			$output = $callback(...$input);
			
			if (!is_array($output) || count($output) !== 2) {
				
				$input = var_dump_to_string($input);
				$output = var_dump_to_string($output);
				
				throw new \UnexpectedValueException(
					"IterTools::map_assoc: callback must return a 2-element " .
					"array [\$key, \$value]. Instead, $output was returned " .
					"when called with input $input."
				);
				
			}
			
			[$key, $value] = $output;
			
			// Normally, only string or integer keys are valid.
			$is_key_valid = is_string($key) || is_int($key);
			
			// If the user has requested key type coercion, then we also
			// consider any non-array, non-object value to be valid.
			// https://www.php.net/manual/en/language.types.array.php#language.types.array.key-casts
			$is_key_coercible = (
				$allow_key_type_coercion &&
				!is_array($key) &&
				!is_object($key)
			);
			
			if (!$is_key_valid && !$is_key_coercible) {
				
				$input = var_dump_to_string($input);
				$key = var_dump_to_string($key);
				
				throw new \UnexpectedValueException(
					"IterTools::map_assoc: callback must return a valid " .
					"array key (string or int). Instead, key $key was " .
					"returned when called with input $input."
				);
				
			}
			
			yield $key => $value;
			
		}
		
	}
	
	function iter_zip(
		array $iterables,
		string $zip_mode = "shortest",
		mixed $fill_value = null,
	): iterable {
		
		return self::map(
			callback: fn(array $entry): array => $entry[1],
			iterables: $iterables,
			zip_mode: $zip_mode,
			fill_value: $fill_value,
		);
		
	}
	
	function zip_keys(array $iterables): iterable {
		
		return;
		
	}
	
}
