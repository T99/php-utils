<?php
/*
 * Created by Trevor Sears <trevor@trevorsears.com> (https://trevorsears.com/).
 * 3:30 PM -- December 15th, 2022.
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
 * Formats an integer value as an amount of bytes, kilobytes, megabytes, etc (or
 * bytes, kibibytes, mebibytes, etc. if the $use_base_2 argument is set to true)
 * to make large byte values more readable to human users.
 * 
 * This function is capable of converting byte to kilobytes, megabytes,
 * gigabytes, etc., but it is also capable of converting to kibabytes,
 * mebibytes, gibibytes, etc. -- which *may be* the expected behavior for some
 * users. While many sources confuse the two units, kilobytes, megabytes,
 * gigabytes, etc., are actually base 10 units and are denoted as KB, MB, GB,
 * etc., while kibabytes, mebibytes, gibibytes, etc. are actually base 2 units
 * and are denoted as KiB, MiB, GiB, etc.
 * 
 * In effect:
 *  - 1000 bytes (B) = 1 kilobyte (KB) = 0.98 kibibyte (KiB)
 *  - 1024 bytes (B) = 1 kibibyte (KiB) = 1.02 kilobyte (KB)
 *  - 1000 kilobytes (KB) = 1 megabyte (MB) = 0.98 mebibytes (MiB)
 *  - 1024 kibibytes (KB) = 1 mebibyte (MiB) = 1.02 megabytes (MB)
 *  - ...
 * 
 * By default, the $use_base_2 argument is set to false, and as such, this
 * function will output values in base 10 (kilobytes, megabytes, gigabytes,
 * etc.). If the output values should be shown in base 2 (kibabytes, mebibytes,
 * gibibytes, etc.), set the $use_base_2 argument to true.
 * 
 * @param int $bytes The number of bytes for which to format a corresponding
 * human-readable string.
 * @param int $max_decimal_places The maximum number of decimal places to use in
 * the output string. Defaults to 1.
 * @param bool $use_base_2 Whether or not to use a base 2 calculation, rather
 * than base 10. Defaults to false.
 * @return string A human-readable string indicating 
 */
function humanReadableBytes(
	int $bytes,
	int $max_decimal_places = 1,
	bool $use_base_2 = false,
): string {
	
	// Define our units.
	$unit_prefixes = ["B", "K", "M", "G", "T", "P"];
	$unit_postfix = $use_base_2 ? "iB" : "B";
	
	// Check which base to use.
	$base = $use_base_2 ? 1024 : 1000;
	
	// Sanitize our bytes to ensure they are positive.
	$is_negative = $bytes < 0;
	if ($is_negative) $bytes *= -1;
	
	// Determine the exponent for the input value, relative to our base.
	$log = log($bytes, $base);
	
	// Determine which unit to use.
	$unit_index = intval($log);
	
	// We can't use a larger unit than the ones we've defined above.
	$unit_index = min($unit_index, count($unit_prefixes) - 1);
	
	// Concatenate together the full unit string.
	$unit = $unit_prefixes[$unit_index];
	if ($unit_index > 0) $unit .= $unit_postfix;
	
	// Determine the modified form of the value to use, according to the units
	// we've decided to use.
	$modified_value = $bytes / pow($base, $unit_index);
	
	// Un-negate our value if we negated it at the beginning.
	if ($is_negative) $modified_value *= -1;
	
	// Returns our resulting value!
	return round($modified_value, $max_decimal_places) . $unit;
	
}
