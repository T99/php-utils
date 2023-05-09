<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 11:30 AM -- December 5th, 2022.
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
 * Indents each line of the provided string (each line being separated by the
 * given line separator, or by the default line separator, '\n') with the
 * specified indentation character/string (a single tab character, by default),
 * and to the specified level (1 by default).
 * 
 * @param string $text The text to indent.
 * @param string $indent The character/string to use to indent each line of the
 * provided text. Defaults to a single tab character.
 * @param string $line_separator The character/string to use to split the
 * provided text into separate lines. Defaults to a single newline character
 * ('\n').
 * @param int $level The number of 'levels' to indent the provided text. Each
 * additional level repeats the indentation character another time. Defaults to
 * 1.
 * @return string The indented version of the input text.
 */
function indent(
	string $text,
	string $indent = "\t",
	string $line_separator = "\n",
	int $level = 1,
): string {
	
	return join($line_separator, array_map(
		callback: fn($line) => str_repeat($indent, $level) . $line,
		array: explode($line_separator, $text),
	));
	
}
