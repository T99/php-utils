<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 4:31 PM -- May 12th, 2023.
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

namespace T99\Util;

/**
 * Provides functions for converting strings between various casing conventions.
 */
class CasingConversion {
	
	/**
	 * Returns an array of words extracted from the input string.
	 *
	 * @param string $text The input string from which to extract words.
	 * @return string[] An array of words extracted from the input string.
	 */
	public static function to_words(string $text): array {
		
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
	 * Returns a converted version of the input string, represented in
	 * 'camelCase'.
	 *
	 * @param string $text The input string to convert to 'camelCase'.
	 * @return string A converted version of the input string, represented in
	 * 'camelCase'.
	 */
	function to_camel_case(string $text): string {
		
		$words = self::to_words($text);
		
		return join("", [
			...array_slice($words, 0, 1),
			self::to_pascal_case(join("_", array_slice($words, 1))),
		]);
		
	}
	
	/**
	 * Returns a converted version of the input string, represented in
	 * 'PascalCase'.
	 *
	 * @param string $text The input string to convert to 'PascalCase'.
	 * @return string A converted version of the input string, represented in
	 * 'PascalCase'.
	 */
	function to_pascal_case(string $text): string {
		
		return join("", array_map(
			callback: fn($word): string => match ($word) {
				"id" => "ID",
				default => ucfirst($word),
			},
			array: self::to_words($text),
		));
		
	}
	
	/**
	 * Returns a converted version of the input string, represented in
	 * 'snake_case'.
	 *
	 * @param string $text The input string to convert to 'snake_case'.
	 * @return string A converted version of the input string, represented in
	 * 'snake_case'.
	 */
	function to_snake_case(string $text): string {
		
		return join(
			separator: "_",
			array: self::to_words($text),
		);
		
	}
	
	/**
	 * Returns a converted version of the input string, represented in
	 * 'SCREAMING_SNAKE_CASE'.
	 *
	 * @param string $text The input string to convert to
	 * 'SCREAMING_SNAKE_CASE'.
	 * @return string A converted version of the input string, represented in
	 * 'SCREAMING_SNAKE_CASE'.
	 */
	function to_screaming_snake_case(string $text): string {
		
		return join("_", array_map(
			callback: fn($word): string => strtoupper($word),
			array: self::to_words($text),
		));
		
	}
	
	/**
	 * Tests the given string against the various casing conversion methods,
	 * outputting the results to the current standard output stream.
	 *
	 * @param string $text The string to test the casing conversion methods
	 * found in this file against.
	 */
	function testCasingConversion(string $text): void {
		
		echo "          Input text: $text\n";
		echo "               Words: [" . join(", ", self::to_words($text)) . "]\n";
		echo "          Camel case: " . self::to_camel_case($text) . "\n";
		echo "         Pascal case: " . self::to_pascal_case($text) . "\n";
		echo "          Snake case: " . self::to_snake_case($text) . "\n";
		echo "Screaming snake case: " . self::to_screaming_snake_case($text) . "\n";
		
	}
	
}
