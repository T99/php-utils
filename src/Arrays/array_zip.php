<?php

function array_zip(array ...$arrays): array {
	
	$zipped_array = [];
	
	foreach (array_simul_iterate_entries(...$arrays) as $entries) {
		
		[$key, $value] = $entries;
		
		$zipped_array[$key] = $value;
		
	}
	
	return $zipped_array;
	
}
