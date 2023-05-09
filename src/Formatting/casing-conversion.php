<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 12:21 PM -- November 21st, 2022.
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

namespace T99\Util\Formatting;

/**
 * Returns an array of words extracted from the input string.
 *
 * @param string $text The input string from which to extract words.
 * @return string[] An array of words extracted from the input string.
 */
function convertToWords(string $text): array {
	
	$matches = [];
	
	preg_match_all(
		pattern: "/(?(?=[A-Z]{2,})[A-Z]+(?=$|_|[A-Z])|[A-Za-z][a-z]*)/",
		subject: $text,
		matches: $matches,
	);
	
	return array_map(
		callback: fn($word): string => strtolower($word),
		array: $matches[0],
	);
	
}

/**
 * Returns a converted version of the input string, represented in 'camelCase'.
 *
 * @param string $text The input string to convert to 'camelCase'.
 * @return string A converted version of the input string, represented in
 * 'camelCase'.
 */
function toCamelCase(string $text): string {
	
	$words = convertToWords($text);
	
	return join("", [
		...array_slice($words, 0, 1),
		toPascalCase(join("_", array_slice($words, 1))),
	]);
	
}

/**
 * Returns a converted version of the input string, represented in 'PascalCase'.
 *
 * @param string $text The input string to convert to 'PascalCase'.
 * @return string A converted version of the input string, represented in
 * 'PascalCase'.
 */
function toPascalCase(string $text): string {
	
	return join("", array_map(
		callback: fn($word): string => match ($word) {
			"id" => "ID",
			default => ucfirst($word),
		},
		array: convertToWords($text),
	));
	
}

/**
 * Returns a converted version of the input string, represented in 'snake_case'.
 *
 * @param string $text The input string to convert to 'snake_case'.
 * @return string A converted version of the input string, represented in
 * 'snake_case'.
 */
function toSnakeCase(string $text): string {
	
	return join(
		separator: "_",
		array: convertToWords($text),
	);
	
}

/**
 * Returns a converted version of the input string, represented in
 * 'SCREAMING_SNAKE_CASE'.
 *
 * @param string $text The input string to convert to 'SCREAMING_SNAKE_CASE'.
 * @return string A converted version of the input string, represented in
 * 'SCREAMING_SNAKE_CASE'.
 */
function toScreamingSnakeCase(string $text): string {
	
	return join("_", array_map(
		callback: fn($word): string => strtoupper($word),
		array: convertToWords($text),
	));
	
}

/**
 * Tests the given string against the various casing conversion methods,
 * outputting the results to the current standard output stream.
 *
 * @param string $text The string to test the casing conversion methods found in
 * this file against.
 */
function testCasingConversion(string $text): void {
	
	echo "          Input text: $text\n";
	echo "               Words: [" . join(", ", convertToWords($text)) . "]\n";
	echo "          Camel case: " . toCamelCase($text) . "\n";
	echo "         Pascal case: " . toPascalCase($text) . "\n";
	echo "          Snake case: " . toSnakeCase($text) . "\n";
	echo "Screaming snake case: " . toScreamingSnakeCase($text) . "\n";
	
}
