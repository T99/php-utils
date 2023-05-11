<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 6:38 PM -- May 9th, 2023.
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
 * Returns a string that represents the contents of the provided array with a
 * comma separated list of values.
 *
 * @param array $input The input array for which to generate a string.
 * @param string $value_when_null The string value to use for null values.
 * Defaults to 'null'.
 * @return string A string that represents the contents of the provided array
 * with a comma separated list of values.
 */
function array_to_string(array $input,
                         string $value_when_null = "null"): string {
	
	$array_contents_string = join(
		separator: ", ",
		array: array_stringify($input, $value_when_null),
	);
	
	return "[$array_contents_string]";
	
}
