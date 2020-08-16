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
 * Adds module specific settings to the settings block.
 *
 * @package   local_courses_clear
 * @copyright 2020, Yuriy Yurinskiy <yuriyyurinskiy@yandex.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function local_courses_clear_extend_settings_navigation($settingsnav, $context)
{
    global $PAGE;

    if (is_siteadmin()) {
        if (!($settingnode = $settingsnav->find('dev_krsk_extend_clear', navigation_node::TYPE_CATEGORY))) {
            if ($settingnode = $settingsnav->find('server', navigation_node::TYPE_SETTING)) {
                $category = navigation_node::create(
                    'Расширенная очистка',
                    null,
                    navigation_node::TYPE_CATEGORY,
                    'dev_krsk_extend_clear',
                    'dev_krsk_extend_clear',
                    new pix_icon('i/settings', 'Расширенная очистка')
                );
                $settingnode->add_node($category);
            }
        }

        if ($settingnode = $settingsnav->find('dev_krsk_extend_clear', navigation_node::TYPE_CATEGORY)) {
            $strfoo = get_string('pluginname', 'local_courses_clear');
            $url = new moodle_url('/local/courses_clear/index.php');
            $foonode = navigation_node::create(
                    $strfoo,
                    $url,
                    navigation_node::TYPE_SETTING,
                    'courses_clear',
                    'courses_clear',
                    new pix_icon('i/settings', $strfoo)
            );
            if ($PAGE->url->compare($url, URL_MATCH_BASE)) {
                $foonode->make_active();
            }
            $settingnode->add_node($foonode);
        }
    }
}