<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 7:55 AM -- May 12th, 2023.
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

use InvalidArgumentException;

/**
 * Returns a flattened version of the input array, up to the specified depth
 * (default: 1).
 * 
 * @param array $array The array to flatten.
 * @param float $max_depth The maximum depth to flatten the array to.
 * @return array The flattened array.
 * @throws InvalidArgumentException If $depth is a negative integer.
 */
function array_flatten(array $array, float $max_depth = INF): array {
	
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
				array_flatten($value, $max_depth - 1),
			);
			
		}
		
	}
	
	return $result;
	
}
