<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 2:10 PM -- May 11th, 2023.
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
 * @param callable | null $callback The callback function to check each element
 * of the input array against. This callback should accept two parameters: the
 * key and the value, and return a boolean indicating whether or not that
 * key-value pair satisfies the condition. If not provided, the default callback
 * checks if the value is truthy.
 * @return bool true if no element from the input array satisfies the condition
 * defined by the specified callback.
 * @see array_all
 * @see array_any
 */
function array_none(
	array $array,
	callable | null $callback = null,
): bool {
	
	$callback ??= fn(int | string $key, mixed $value): bool => !!$value;
	
	foreach ($array as $key => $value) {
		
		if ($callback($value, $key)) return true;
		
	}
	
	return false;
	
}