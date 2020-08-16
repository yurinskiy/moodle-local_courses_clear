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
 * Main file plugin
 *
 * @package   local_courses_clear
 * @copyright 2020, Yuriy Yurinskiy <yuriyyurinskiy@yandex.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once('courses_reset_form.php');

global $DB;

/** Проверяем авторизован ли пользователь */
require_login();

/** Проверяем права пользователя */
if (!is_siteadmin()) {
    header('Location: ' . $CFG->wwwroot);
    die();
}

$url = new moodle_url('/local/courses_clear/index.php');
$systemcontext = $context = context_system::instance();

$pagetitle = get_string('pluginname', 'local_courses_clear');
$pageheading = format_string($SITE->fullname, true, array('context' => $systemcontext));

$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pageheading);

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);

$mform = new courses_reset_form();

if ($data = $mform->get_data()) { // no magic quotes
    $courses = $DB->get_records('course', array('category' => $data->categoryid));
    unset($data->categoryid);

    $datatable=[];

    foreach ($courses as $course) {
        $data->id = $course->id;
        $data->reset_start_date_old = $course->startdate;
        $data->reset_end_date_old = $course->enddate;

        $status = reset_course_userdata($data);

        foreach ($status as $item) {
            $line = array();
            $line[] = $course->id;
            $line[] = $course->shortname;
            $line[] = $item['component'];
            $line[] = $item['item'];
            $line[] = ($item['error']===false) ? get_string('ok') : '<div class="notifyproblem">'.$item['error'].'</div>';
            $datatable[] = $line;
        }
    }

    $table = new html_table();
    $table->head  = array('Ид курса', get_string('course'), get_string('resetcomponent'), get_string('resettask'), get_string('resetstatus'));
    $table->size  = array('5%','20%', '15%', '30%', '30%');
    $table->align = array('left', 'left', 'left', 'left', 'left');
    $table->width = '80%';
    $table->data  = $datatable;
    echo html_writer::table($table);

    echo $OUTPUT->continue_button($url);  // Back to course page
    echo $OUTPUT->footer();

    exit;
}

$mform->display();

echo $OUTPUT->footer();