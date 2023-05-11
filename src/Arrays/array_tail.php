<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 2:26 PM -- May 11th, 2023.
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
 * Returns the last $count elements of the given array, or all but the first
 * $count elements if $count is negative.
 * 
 * @param array $array The array to get trailing elements from.
 * @param int $count The number of trailing elements to retrieve, or the number
 * of leading elements to exclude if negative.
 * @param bool $preserve_keys By default, the returned array will have been
 * re-indexed. If this parameter is set to true, the array keys will be
 * preserved.
 * @return array The last $count elements of the given array, or all but the
 * first $count elements if $count is negative. 
 */
function array_tail(
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
