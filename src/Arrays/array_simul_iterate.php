<?php

namespace T99\Util\Arrays;

use ArrayIterator;

/**
 * Iterates over multiple arrays simultaneously, yielding an array of entries
 * from each array on each iteration.
 * 
 * @param array ...$arrays The arrays to iterate over.
 * @return iterable An iterable that yields an array of entries from each array
 * on each iteration.
 */
function array_simul_iterate_entries(array ...$arrays): iterable {
	
	$iterators = array_map(
		callback: fn(array $array): ArrayIterator => new ArrayIterator($array),
		array: $arrays,
	);
	
	while (true) {
		
		$should_continue = false;
		$yield_array = [];
		
		foreach ($iterators as $iterator) {
			
			if ($iterator->valid()) {
				
				$should_continue = true;
				$yield_array[] = [$iterator->key(), $iterator->current()];
				$iterator->next();
				
			} else {
				
				$yield_array[] = null;
				
			}
			
		}
		
		if ($should_continue) yield $yield_array;
		else break;
		
	}
	
}

/**
 * Iterates over multiple arrays simultaneously, yielding an array of values
 * from each array on each iteration.
 * 
 * @param array ...$arrays The arrays to iterate over.
 * @return iterable An iterable that yields an array of values from each array
 * on each iteration.
 */
function array_simul_iterate(array ...$arrays): iterable {
	
	foreach (array_simul_iterate_entries(...$arrays) as $entries) {
		
		yield array_map(
			callback: fn($entry): mixed => $entry[1],
			array: $entries,
		);
		
	}
	
}

/**
 * Iterates over multiple arrays simultaneously, yielding an array of keys
 * from each array on each iteration.
 * 
 * @param array ...$arrays The arrays to iterate over.
 * @return iterable An iterable that yields an array of keys from each array
 * on each iteration.
 */
function array_simul_iterate_keys(array ...$arrays): iterable {
	
	foreach (array_simul_iterate_entries(...$arrays) as $entries) {
		
		yield array_map(
			callback: fn($entry): mixed => $entry[0],
			array: $entries,
		);
		
	}
	
}
