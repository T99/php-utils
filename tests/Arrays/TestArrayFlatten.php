<?php

namespace T99\Util\Tests\Arrays;

require __DIR__ . "/../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use function T99\Util\Arrays\array_flatten;

/**
 * Unit tests for the `array_flatten` function.
 */
class TestArrayFlatten extends TestCase {
	
	public function testEmptyArray(): void {
		
		$this->assertEquals(
			expected: [],
			actual: array_flatten([]),
			message: "Empty array should be flattened to an empty array.",
		);
		
	}
	
	public function testBasicMultidimensionalArray(): void {
		
		$this->assertEquals(
			expected: [1, 2, 3, 4, 5, 6],
			actual: array_flatten([[1, 2, 3], [4, 5, 6]]),
			message: "Basic multidimensional array should be flattened.",
		);
		
	}
	
	public function testDepth(): void {
		
		$this->assertEquals(
			expected: [1, 2, 3, [4, 5, 6], [7, 8, 9]],
			actual: array_flatten([[1, 2, 3], [[4, 5, 6], [7, 8, 9]]], 1),
			message: "Depth of 1 should not flatten beyond the first level.",
		);
		
	}
	
	public function testDeeplyNestedArray(): void {
		
		$this->assertEquals(
			expected: [1, 2, 3, 4, 5, 6, [7, 8, 9]],
			actual: array_flatten([[1, 2, 3], [[4, 5, 6], [[7, 8, 9]]]], 2),
			message: "Depth of 2 should not flatten beyond the second level.",
		);
		
	}
	
}
