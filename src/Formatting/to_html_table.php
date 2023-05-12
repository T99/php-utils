<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 8:36 AM -- May 11th, 2023.
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

function to_html_table(array $data, array $headers = null): string {
	
	// If the data is empty, return an empty string.
	if ($data === []) return "";
	
	// If the headers are not provided, use the keys of the first row of data.
	if ($headers === null) $headers = array_keys($data[0]);
	
	// Create the table header.
	$table_header = "<thead><tr>";
	
	foreach ($headers as $header) $table_header .= "<th>$header</th>";
	
	$table_header .= "</tr></thead>";
	
	// Create the table body.
	$table_body = "<tbody>";
	
	foreach ($data as $row) {
		
		$table_body .= "<tr>";
		
		foreach ($row as $value) $table_body .= "<td>$value</td>";
		
		$table_body .= "</tr>";
	}
	
	$table_body .= "</tbody>";
	
	// Return the table.
	return "<table>$table_header$table_body</table>";
	
}
