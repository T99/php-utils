<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 10:43 AM -- May 11th, 2023.
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

namespace T99\Util\Tests\Arrays;

require __DIR__ . "/../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use function T99\Util\Arrays\array_map_assoc;

class TestArrayMapAssoc extends TestCase {
	
	public function testArrayMapAssocZipsInputArraysIfNoCallbackIsProvided(): void {
		
		$this->assertEquals(
			expected: [
				[1, 4],
				[2, 5],
				[3, 6],
			],
			actual: array_map_assoc(null, [1, 2, 3], [4, 5, 6]),
			message: "did not zip input arrays when no callback was provided.",
		);
		
	}
	
	// test that array_map_assoc zips associative arrays when no callback is provided
	public function testArrayMapAssocZipsAssociativeArraysIfNoCallbackIsProvided(): void {
		
		$this->assertEquals(
			expected: [
				["a", 1, "b", 4],
				["c", 2, "d", 5],
				["e", 3, "f", 6],
			],
			actual: array_map_assoc(
				null,
				["a" => 1, "c" => 2, "e" => 3],
				["b" => 4, "d" => 5, "f" => 6],
			),
			message: "did not zip associative arrays when no callback was provided.",
		);
		
	}
	
}
