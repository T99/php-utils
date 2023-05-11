<?php

namespace T99\Util\Tests\Arrays;

require __DIR__ . "/../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use function T99\Util\Arrays\array_tail;

class TestArrayTail extends TestCase {
	
	public function testEmptyArray(): void {
		
		$this->assertEquals(
			expected: [],
			actual: array_tail([]),
			message: "Getting the tail of an empty array failed to return an " .
				"empty array."
		);
		
	}
	
	public function testBasicArray(): void {
		
		$this->assertEquals(
			expected: [3],
			actual: array_tail([1, 2, 3]),
			message: "Getting the tail of a basic array failed to return the " .
				"correct elements."
		);
		
	}
	
	public function testBasicArrayWithCount(): void {
		
		$this->assertEquals(
			expected: [2, 3],
			actual: array_tail([1, 2, 3], 2),
			message: "Getting the tail of a basic array with a count failed " .
				"to return the correct elements."
		);
		
	}
	
	public function testBasicArrayWithNegativeCount(): void {
		
		$this->assertEquals(
			expected: [3, 4, 5],
			actual: array_tail([1, 2, 3, 4, 5], -2),
			message: "Getting the tail of a basic array with a negative " .
				"count failed to return the correct elements."
		);
		
	}
	
	public function testNestedArray(): void {
		
		$this->assertEquals(
			expected: [[7, 8, 9]],
			actual: array_tail([[1, 2, 3], [4, 5, 6], [7, 8, 9]]),
			message: "Getting the tail of a nested array failed to return " .
				"the correct elements."
		);
		
	}
	
	public function testNestedArrayWithCount(): void {
		
		$this->assertEquals(
			expected: [[4, 5, 6], [7, 8, 9]],
			actual: array_tail([[1, 2, 3], [4, 5, 6], [7, 8, 9]], 2),
			message: "Getting the tail of a nested array with a count failed " .
					  "to return the correct elements."
		);
		
	}
	
	public function testNestedArrayWithNegativeCount(): void {
		
		$this->assertEquals(
			expected: [[4, 5, 6], [7, 8, 9]],
			actual: array_tail([[1, 2, 3], [4, 5, 6], [7, 8, 9]], -1),
			message: "Getting the tail of a nested array with a negative " .
				"count failed to return the correct elements."
		);
		
	}
	
	public function testAssociativeArray(): void {
		
		$this->assertEquals(
			expected: ["c" => 3],
			actual: array_tail(["a" => 1, "b" => 2, "c" => 3]),
			message: "Getting the tail of an associative array failed to " .
				"return the correct elements."
		);
		
	}
	
	public function testAssociativeArrayWithCount(): void {
		
		$this->assertEquals(
			expected: ["b" => 2, "c" => 3],
			actual: array_tail(["a" => 1, "b" => 2, "c" => 3], 2),
			message: "Getting the tail of an associative array with a count " .
				"failed to return the correct elements."
		);
		
	}
	
	public function testAssociativeArrayWithNegativeCount(): void {
		
		$this->assertEquals(
			expected: ["b" => 2, "c" => 3, "d" => 4],
			actual: array_tail(["a" => 1, "b" => 2, "c" => 3, "d" => 4], -1),
			message: "Getting the head of an associative array with a " .
				"negative count failed to return the correct elements."
		);
		
	}
	
}
