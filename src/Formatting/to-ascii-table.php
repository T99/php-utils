<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 8:35 AM -- December 12th, 2022.
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

namespace T99\Util\Formatting;

use function T99\Util\Arrays\array_stringify;
use function T99\Util\Arrays\is_assoc;

/**
 * Returns a string containing an ASCII table.
 * 
 * @param array $data An array of data to populate the resulting table with.
 * @param array | null $headers An optional array of 'headers' to use when
 * building the resulting table. If this array is a simple string array, it will
 * be used to narrow and potentially re-order the result set according to the
 * order and presence of a given column within this array. If this array is
 * associative, the keys of the array will function similarly to what was just
 * described, while the values of each key will be used as a replacement for
 * the header for the column being described. 
 * @return string A string containing an ASCII table.
 */
function toASCIITable(array $data, array $headers = null): string {
	
	$result = "";
	
	if (count($data) <= 0) return $result;
	else if (is_assoc($data)) {
		
		$data_keys = array_keys($data);
		$data_values = array_stringify(array_values($data), "");
		
		$max_key_length = max(array_map(
			callback: fn($key): int => strlen($key),
			array: $data_keys,
		));
		
		$max_value_length = max(array_map(
			callback: fn($value): int => strlen($value),
			array: $data_values,
		));
		
		$horizontal_separator = join("", [
			"+-",
			str_repeat("-", $max_key_length),
			"-+-",
			str_repeat("-", $max_value_length),
			"-+",
		]);
		
		$result .= "$horizontal_separator\n";
		$result .= join("\n", array_map(
			fn($key, $value): string => join("", [
				"| ",
				str_pad($key, $max_key_length),
				" | ",
				str_pad($value, $max_value_length),
				" |\n",
				$horizontal_separator,
			]),
			$data_keys, $data_values,
		));
		$result .= "\n";
		
		return $result;
		
	}
	
	$data = array_map(
		callback: fn($row) => is_object($row) ? get_object_vars($row) : $row,
		array: $data,
	);
	
	if ($headers !== null && is_assoc($headers)) {
		
		$header_columns = array_keys($headers);
		$header_titles = array_values($headers);
		
	} else {
		
		$header_columns = $headers ?? array_keys($data[0]);
		$header_titles = $header_columns;
		
	}
	
	$data = array_map(
		callback: fn($row): array => array_stringify(array_filter(
			array: $row,
			callback: fn($key): bool => in_array($key, $header_columns),
			mode: ARRAY_FILTER_USE_KEY,
		), ""),
		array: $data,
	);
	
	$column_widths = array_map(
		callback: fn($text): int => strlen($text),
		array: $header_titles,
	);
	
	foreach ($data as $row) {
		
		for ($i = 0; $i < count($column_widths); $i++) {
			
			$row[$header_columns[$i]] ??= "";
			
			$column_widths[$i] = max(
				$column_widths[$i],
				strlen($row[$header_columns[$i]]),
			);
			
		}
		
	}
	
	$horizontal_separator = join("", [
		"+",
		join("+", array_map(
			callback: fn($width): string => str_repeat("-", $width + 2),
			array: $column_widths,
		)),
		"+"
	]);
	
	$result .= "$horizontal_separator\n";
	
	$result .= join("", [
		"| ",
		join(" | ", array_map(
			fn($header_title, $width): string => str_pad($header_title, $width),
			$header_titles, $column_widths,
		)),
		" |\n",
		str_replace("-", "=", $horizontal_separator),
		"\n",
	]);
	
	foreach ($data as $row) {
		
		$result .= join("", [
			"| ",
			join(" | ", array_map(
				fn($column, $width): string =>
					str_pad($row[$column] ?? "", $width),
				$header_columns, $column_widths,
			)),
			" |\n",
			$horizontal_separator,
			"\n",
		]);
		
	}
	
	return $result;
	
}
