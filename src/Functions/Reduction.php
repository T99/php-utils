<?php

namespace T99\Util\Functions;

/**
 * A collection of functions that can be used as the callback for
 * array_reduce().
 * 
 * @see https://www.php.net/manual/en/function.array-reduce.php
 */
class Reduction {
	
	/**
	 * Returns a callable that can be used with array_reduce() to sum the values
	 * of an array.
	 * 
	 * @return callable A callable that can be used with array_reduce() to sum
	 * the values of an array.
	 */
	public static function SUM(): callable {
		
		return fn(mixed $accumulator, mixed $current): mixed =>
			$accumulator + $current;
		
	}
	
	/**
	 * Returns a callable that can be used with array_reduce() to multiply the
	 * values of an array.
	 * 
	 * @return callable A callable that can be used with array_reduce() to
	 * multiply the values of an array.
	 */
	public static function PRODUCT(): callable {
		
		return fn(mixed $accumulator, mixed $current): int | float =>
			$accumulator * $current;
		
	}
	
	/**
	 * Returns a callable that can be used with array_reduce() to return the
	 * minimum value of an array.
	 * 
	 * @return callable A callable that can be used with array_reduce() to
	 * return the minimum value of an array.
	 */
	public static function MIN(): callable {
		
		return fn(mixed $accumulator, mixed $current): mixed =>
			min($accumulator, $current);
		
	}
	
	/**
	 * Returns a callable that can be used with array_reduce() to return the
	 * maximum value of an array.
	 * 
	 * @return callable A callable that can be used with array_reduce() to
	 * return the maximum value of an array.
	 */
	public static function MAX(): callable {
		
		return fn(mixed $accumulator, mixed $current): mixed =>
			max($accumulator, $current);
		
	}
	
	/**
	 * Returns a callable that can be used with array_reduce() to return a
	 * string containing the concatenation of all the values of an array.
	 * 
	 * @return callable A callable that can be used with array_reduce() to
	 * return a string containing the concatenation of all the values of an
	 * array.
	 */
	public static function CONCAT(): callable {
		
		return fn(mixed $accumulator, mixed $current): string =>
			$accumulator . $current;
		
	}
	
	public static function AND(): callable {
		
		return fn(mixed $accumulator, mixed $current): bool =>
			$accumulator && $current;
		
	}
	
	public static function OR(): callable {
		
		return fn(mixed $accumulator, mixed $current): bool =>
			$accumulator || $current;
		
	}
	
	public static function XOR(): callable {
		
		return fn(mixed $accumulator, mixed $current): bool =>
			$accumulator xor $current;
		
	}
	
	/**
	 * Returns a callable that can be used with array_reduce() to return the
	 * number of values in an array.
	 * 
	 * @return callable A callable that can be used with array_reduce() to
	 * return the number of values in an array.
	 */
	public static function COUNT(): callable {
		
		return fn(mixed $accumulator, mixed $current): int => $accumulator + 1;
		
	}
	
	public static function COUNT_CONDITION(callable $condition): callable {
		
		return fn(mixed $accumulator, mixed $current): int =>
			$accumulator + ($condition($current) ? 1 : 0);
		
	}
	
	public static function COUNT_INSTANCES(array ...$values): callable {
		
		return self::COUNT_CONDITION(
			fn(mixed $current): bool => in_array($current, $values),
		);
		
	}
	
	public static function COUNT_NULLS(): callable {
		
		return self::COUNT_CONDITION(
			fn(mixed $current): bool => $current === null,
		);
		
	}
	
	public static function COUNT_NOT_NULLS(): callable {
		
		return self::COUNT_CONDITION(
			fn(mixed $current): bool => $current !== null,
		);
		
	}
	
	public static function COUNT_TRUTHY(): callable {
		
		return self::COUNT_CONDITION(
			fn(mixed $current): bool => !!$current,
		);
		
	}
	
	public static function COUNT_FALSY(): callable {
		
		return self::COUNT_CONDITION(
			fn(mixed $current): bool => !$current,
		);
		
	}
	
	public static function COUNT_POSITIVE(bool $count_zero = true): callable {
		
		if ($count_zero) {
			
			return self::COUNT_CONDITION(
				fn(mixed $current): bool => $current >= 0,
			);
			
		} else {
			
			return self::COUNT_CONDITION(
				fn(mixed $current): bool => $current > 0,
			);
			
		}
		
	}
	
	public static function COUNT_NEGATIVE(bool $count_zero = false): callable {
		
		if ($count_zero) {
			
			return self::COUNT_CONDITION(
				fn(mixed $current): bool => $current <= 0,
			);
			
		} else {
			
			return self::COUNT_CONDITION(
				fn(mixed $current): bool => $current < 0,
			);
			
		}
		
	}
	
	public static function COUNT_ODD(): callable {
		
		return self::COUNT_CONDITION(
			fn(mixed $current): bool => $current % 2 !== 0,
		);
		
	}
	
	public static function COUNT_EVEN(): callable {
		
		return self::COUNT_CONDITION(
			fn(mixed $current): bool => $current % 2 === 0,
		);
		
	}
	
}
