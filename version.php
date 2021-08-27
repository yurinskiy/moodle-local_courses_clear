<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version details.
 *
 * @package   local_courses_clear
 * @copyright 2020, Yuriy Yurinskiy <yuriyyurinskiy@yandex.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$plugin->version   = 2021082700;				// Plugin released on 16th August 2020.
$plugin->requires  = 2018120300;				// Moodle 3.6.0 is required.
$plugin->supported = [36,39];   					// Moodle 3.6.x are supported. Available as of Moodle 3.9.0 or later.
$plugin->component = 'local_courses_clear';	// Declare the type and name of this plugin. Required  as of Moodle 3.0 or later.
$plugin->maturity = MATURITY_STABLE;				// This is considered as ready for production sites.
$plugin->release = '1.0.0';						// This is our first release.

$plugin->dependencies = [
    'mod_data' => ANY_VERSION					// The Data Module activity must be present (any version).
];
