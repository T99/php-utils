<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 6:21 PM -- May 9th, 2023.
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
