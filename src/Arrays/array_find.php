<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 10:36 AM -- May 11th, 2023.
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

namespace T99\Util\Arrays;

/**
 * Returns the first key-value pair from the input array for which the callback
 * function returns true.
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
 * @param array $array The array to search.
 * @param callable | null $callback The callback function to use. This callback
 * should accept two parameters: the key and the value, and return a boolean
 * indicating whether or not that key-value pair satisfies the condition. If not
 * provided, the default callback checks if the value is truthy.
 * @return array | null The first key-value pair from the input array for which
 * the callback function returns true, or null if no such pair exists.
 */
function array_find_entry(
	array $array,
	callable | null $callback = null,
): array | null {
	
	$callback ??= fn(int | string $key, mixed $value): bool => !!$value;
	
	foreach ($array as $key => $value) {
		
		if ($callback($value, $key)) return [$key, $value];
		
	}
	
	return null;
	
}

/**
 * Returns the first key-value pair from the input array for which the callback
 * function returns true.
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
 * @param array $array The array to search.
 * @param callable | null $callback The callback function to use. This callback
 * should accept two parameters: the key and the value, and return a boolean
 * indicating whether or not that key-value pair satisfies the condition. If not
 * provided, the default callback checks if the value is truthy.
 * @return array | null The first key-value pair from the input array for which
 * the callback function returns true, or null if no such pair exists.
 */
function array_find_last_entry(
	array $array,
	callable | null $callback = null,
): array | null {
	
	$callback ??= fn(int | string $key, mixed $value): bool => !!$value;
	
	for (end($array); ($key = key($array)) !== null; prev($array)) {
		
		$value = current($array);
		
		if ($callback($value, $key)) return [$key, $value];
		
	}
	
	return null;
	
}

/**
 * Returns the value of the first key-value pair from the input array for which
 * the callback function returns true.
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
 * @param array $array The array to search.
 * @param callable | null $callback The callback function to use. This callback
 * should accept two parameters: the key and the value, and return a boolean
 * indicating whether or not that key-value pair satisfies the condition. If not
 * provided, the default callback checks if the value is truthy.
 * @return int | string | null The value of the first key-value pair from the
 * input array for which the callback function returns true, or null if no such
 * pair exists.
 */
function array_find(
	array $array,
	callable | null $callback = null,
): mixed {
	
	return array_find_entry($array, $callback)[1] ?? null;
	
}

/**
 * Returns the value of the last key-value pair from the input array for which
 * the callback function returns true.
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
 * @param array $array The array to search.
 * @param callable | null $callback The callback function to use. This callback
 * should accept two parameters: the key and the value, and return a boolean
 * indicating whether or not that key-value pair satisfies the condition. If not
 * provided, the default callback checks if the value is truthy.
 * @return int | string | null The value of the last key-value pair from the
 * input array for which the callback function returns true, or null if no such
 * pair exists.
 */
function array_find_last(
	array $array,
	callable | null $callback = null,
): mixed {
	
	return array_find_last_entry($array, $callback)[1] ?? null;
	
}

/**
 * Returns the key of the first key-value pair from the input array for which
 * the callback function returns true.
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
 * @param array $array The array to search.
 * @param callable | null $callback The callback function to use. This callback
 * should accept two parameters: the key and the value, and return a boolean
 * indicating whether or not that key-value pair satisfies the condition. If not
 * provided, the default callback checks if the value is truthy.
 * @return int | string | null The key of the first key-value pair from the
 * input array for which the callback function returns true, or null if no such
 * pair exists.
 */
function array_find_key(
	array $array,
	callable | null $callback = null,
): int | string | null {
	
	return array_find_entry($array, $callback)[0] ?? null;
	
}

/**
 * Returns the key of the last key-value pair from the input array for which
 * the callback function returns true.
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
 * @param array $array The array to search.
 * @param callable | null $callback The callback function to use. This callback
 * should accept two parameters: the key and the value, and return a boolean
 * indicating whether or not that key-value pair satisfies the condition. If not
 * provided, the default callback checks if the value is truthy.
 * @return int | string | null The key of the last key-value pair from the
 * input array for which the callback function returns true, or null if no such
 * pair exists.
 */
function array_find_last_key(
	array $array,
	callable | null $callback = null,
): int | string | null {
	
	return array_find_last_entry($array, $callback)[0] ?? null;
	
}
