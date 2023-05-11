<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 3:36 PM -- May 9th, 2023.
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

use function T99\Util\Arrays\array_entries;
use function T99\Util\Arrays\array_largest_countable;
use function T99\Util\Formatting\var_dump_to_string;

/**
 * Applies a callback function to the key-value pairs of an array, returning a
 * new associative array.
 * 
 * The callback is expected to be in the following form:
 * ```
 * fn(array $entry[, array | null $entry2, ...]) => [string, string]
 * ```
 * Where each $entry parameter is a [key, value] tuple, comprised of a key-value
 * pair from the input array in its respective position, and the returned value
 * is a tuple wherein the first value is the newly mapped key, and the second
 * value is the newly mapped value. 
 *
 * @param callable | null $callback The callback function to apply to each
 * key-value pair.
 * @param array $array The input array to be processed.
 * @param array $arrays Additional arrays to be processed.
 * @return array The resulting associative array.
 * @throws Exception If the callback returns either a value that is not an
 * array, or an array value containing fewer than 2 indices.
 */
function array_map_assoc(
	callable | null $callback,
	array $array,
	array ...$arrays,
): array {
	
	$rows = [$array, ...$arrays];
	$rows_entries = array_map("array_entries", $rows);
	$max_number_of_columns = array_largest_countable($rows);
	$columns = array_map(
		callback: fn(int $column_index): array => array_map(
			callback: fn(array $row): ?array => $row[$column_index] ?? null,
			array: $rows_entries,
		),
		array: range(0, $max_number_of_columns - 1),
	);
	
	$result = [];
	
	foreach ($columns as $column) {
		
		$entry = $callback(...$column);
		
		if (!is_array($entry)) {
			
			throw new Exception(
				"array_map_assoc failed: result returned from callback was " .
				"not an array at when supplied with: " .
				var_dump_to_string($entry)
			);
			
		}
		
		if (count($entry) < 2) {
			
			throw new Exception(
				"array_map_assoc failed: result returned from callback " .
				"contained fewer than 2 values when supplied with: " .
				var_dump_to_string($entry)
			);
			
		}
		
		[$mapped_key, $mapped_value] = $entry;
		$result[strval($mapped_key)] = $mapped_value;
		
	}
	
	return $result;
	
}
