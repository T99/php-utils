<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 2:21 PM -- December 12th, 2022.
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

/**
 * Given a 2-dimensional array, this function returns a mapped 2-dimensional
 * array wherein the rows of the input array have been 'grouped'.
 * 
 * 'Grouping' involves merging similar rows of the input array. Rows are
 * considered 'similar' if some subset of their fields are found to be
 * equivalent to each other. This subset of fields is identified using the
 * '$group_by' argument. 'Merging' involves the creation of an array of the
 * values of similar rows, wherein this array of similar rows is substituted in
 * as the value of the field from which the array was created for the 'grouped'
 * row. The fields that will be merged can be decided using the '$group_into'
 * argument.
 * 
 * Effectively, this function serves a very similar purpose as the SQL
 * 'GROUP BY' statement.
 * 
 * @param array $input The input 2-dimensional array to group.
 * @param array $group_by The fields that will be used to decide if two rows
 * should be merged.
 * @param array $group_into The fields that will be merged for rows determined
 * to be 'similar'.
 * @return array The resulting grouped array.
 */
function groupArray(array $input, array $group_by, array $group_into): array {
	
	$results = [];
	$current_item = null;
	$current_group = array_fill(0, count($group_by), "");
	
	foreach ($input as $item) {
		
		$is_item_object = is_object($item);
		
		$item = (array) $item;
		
		$item_group = array_map(
			callback: fn($group_by_field) => $item[$group_by_field],
			array: $group_by,
		);
		
		if ($item_group !== $current_group) {
			
			if (!is_null($current_item)) {
				
				if ($is_item_object) $results[] = (object) $current_item;
				else $results[] = $current_item;
				
			}
			
			$current_group = $item_group;
			$current_item = $item;
			
			foreach ($group_into as $group_into_field) {
				
				$current_item[$group_into_field] = [];
			
			}
			
		}
		
		foreach ($group_into as $group_into_field) {
			
			$current_item[$group_into_field][] = $item[$group_into_field];
			
		}
		
	}
	
	if (!is_null($current_item)) {
		
		if ($is_item_object) $results[] = (object) $current_item;
		else $results[] = $current_item;
		
	}
	
	return $results;
	
}
