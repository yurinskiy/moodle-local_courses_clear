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
 * Provides the courses_reset_form class.
 *
 * @package     core
 * @copyright   2007 Petr Skoda
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once $CFG->libdir.'/formslib.php';
require_once($CFG->dirroot . '/course/lib.php');

/**
 * Defines the course reset settings form.
 *
 * @copyright   2007 Petr Skoda
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class courses_reset_form extends moodleform {
    function definition (){
        global $CFG, $COURSE, $DB;

        $mform =& $this->_form;

        $mform->addElement('header', 'generalheader', get_string('general'));

        $options = array();
        $options[0] = get_string('top');
        $options += core_course_category::make_categories_list('moodle/category:manage');

        $mform->addElement('select', 'categoryid', get_string('categories'), $options);
        $mform->setDefault('categoryid', $this->_customdata['categoryid']);

        $mform->addElement('date_time_selector', 'reset_start_date', get_string('startdate'), array('optional' => true));
        $mform->addHelpButton('reset_start_date', 'startdate');
        $mform->addElement('date_time_selector', 'reset_end_date', get_string('enddate'), array('optional' => true));
        $mform->addHelpButton('reset_end_date', 'enddate');
        $mform->addElement('checkbox', 'reset_events', get_string('deleteevents', 'calendar'));
        $mform->addElement('checkbox', 'reset_notes', get_string('deletenotes', 'notes'));
        $mform->addElement('checkbox', 'reset_comments', get_string('deleteallcomments', 'moodle'));
        $mform->addElement('checkbox', 'reset_completion', get_string('deletecompletiondata', 'completion'));
        $mform->addElement('checkbox', 'delete_blog_associations', get_string('deleteblogassociations', 'blog'));
        $mform->addHelpButton('delete_blog_associations', 'deleteblogassociations', 'blog');
        $mform->addElement('checkbox', 'reset_competency_ratings', get_string('deletecompetencyratings', 'core_competency'));

//        $mform->addElement('header', 'rolesheader', get_string('roles'));
//
//        $roles = get_assignable_roles(context_course::instance($COURSE->id));
//        $roles[0] = get_string('noroles', 'role');
//        $roles = array_reverse($roles, true);
//
//        $mform->addElement('select', 'unenrol_users', get_string('unenrolroleusers', 'enrol'), $roles, array('multiple' => 'multiple'));
//        $mform->addElement('checkbox', 'reset_roles_overrides', get_string('deletecourseoverrides', 'role'));
//        $mform->setAdvanced('reset_roles_overrides');
//        $mform->addElement('checkbox', 'reset_roles_local', get_string('deletelocalroles', 'role'));


        $mform->addElement('header', 'gradebookheader', get_string('gradebook', 'grades'));

        $mform->addElement('checkbox', 'reset_gradebook_items', get_string('removeallcourseitems', 'grades'));
        $mform->addHelpButton('reset_gradebook_items', 'removeallcourseitems', 'grades');
        $mform->addElement('checkbox', 'reset_gradebook_grades', get_string('removeallcoursegrades', 'grades'));
        $mform->addHelpButton('reset_gradebook_grades', 'removeallcoursegrades', 'grades');
        $mform->disabledIf('reset_gradebook_grades', 'reset_gradebook_items', 'checked');


        $mform->addElement('header', 'groupheader', get_string('groups'));

        $mform->addElement('checkbox', 'reset_groups_remove', get_string('deleteallgroups', 'group'));
        $mform->addElement('checkbox', 'reset_groups_members', get_string('removegroupsmembers', 'group'));
        $mform->disabledIf('reset_groups_members', 'reset_groups_remove', 'checked');

        $mform->addElement('checkbox', 'reset_groupings_remove', get_string('deleteallgroupings', 'group'));
        $mform->addElement('checkbox', 'reset_groupings_members', get_string('removegroupingsmembers', 'group'));
        $mform->disabledIf('reset_groupings_members', 'reset_groupings_remove', 'checked');

        $unsupported_mods = array();
        if ($allmods = $DB->get_records('modules') ) {
            foreach ($allmods as $mod) {
                $modname = $mod->name;
                $modfile = $CFG->dirroot."/mod/$modname/lib.php";
                $mod_reset_course_form_definition = $modname.'_reset_course_form_definition';
                $mod_reset__userdata = $modname.'_reset_userdata';
                if (file_exists($modfile)) {
//                    if (!$DB->count_records($modname, array('course'=>$COURSE->id))) {
//                        continue; // Skip mods with no instances
//                    }
                    include_once($modfile);
                    if (function_exists($mod_reset_course_form_definition)) {
                        $mod_reset_course_form_definition($mform);
                    } else if (!function_exists($mod_reset__userdata)) {
                        $unsupported_mods[] = $mod;
                    }
                } else {
                    debugging('Missing lib.php in '.$modname.' module');
                }
            }
        }
        // mention unsupported mods
        if (!empty($unsupported_mods)) {
            $mform->addElement('header', 'unsupportedheader', get_string('resetnotimplemented'));
            foreach($unsupported_mods as $mod) {
                $mform->addElement('static', 'unsup'.$mod->name, get_string('modulenameplural', $mod->name));
                $mform->setAdvanced('unsup'.$mod->name);
            }
        }

        $mform->setType('id', PARAM_INT);

        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('resetcourse'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
    }
}