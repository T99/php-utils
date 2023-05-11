<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 6:33 PM -- May 9th, 2023.
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
 * Returns an array of strings, representing the values of the input array after
 * each being converted to a string.
 *
 * @param array $input The input array to map to a string array.
 * @param string $value_when_null The string value to use for null values.
 * Defaults to 'null'.
 * @return string[] An array of strings, representing the values of the input
 * array after each being converted to a string.
 */
function array_stringify(array $input,
                         string $value_when_null = "null"): array {
	
	return array_map(
		callback: fn($value): string => match (gettype($value)) {
			"boolean" => $value ? "true" : "false",
			"integer", "double" => strval($value),
			"string" => $value,
			"array" => array_to_string($value, $value_when_null),
			"NULL" => $value_when_null,
			default => "<" . gettype($value) . ">",
		},
		array: $input,
	);
	
}
