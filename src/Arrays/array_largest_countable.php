<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 3:52 PM -- May 9th, 2023.
 * Project: php-utils
 * 
 * php-utils
 * Copyright (C) 2022 Trevor Sears
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

use Countable;

/**
 * Finds the largest {@link Countable} (or array) in the provided array.
 * 
 * @param (Countable | array)[] ...$countables An array of Countables (or
 * arrays).
 * @return int The size of the largest Countable (or array) found in the
 * provided array.
 */
function array_largest_countable(array ...$countables): int {
	
	return array_reduce(
		array: $countables,
		callback:
			fn(?int $current_largest, Countable | array $countable): int =>
				max($current_largest, count($countable)),
		initial: null,
	);
	
}
