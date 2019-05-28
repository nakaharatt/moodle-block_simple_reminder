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
 * @package    block_simple_reminder
 * @copyright  Takahiro Nakahara <nakahara@3strings.co.jp>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_simple_reminder extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_simple_reminder');
    }

    function get_content() {
        global $CFG, $OUTPUT, $DB;

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        // user/index.php expect course context, so get one if page has module context.
        $currentcontext = $this->page->context->get_course_context(false);
        
        $this->content->text = '';
        
        $simplereminder = $DB->get_record('block_simple_reminder',array('block'=>$this->instance->id));
        if($simplereminder){
            if($simplereminder->enable){
                $cm = $DB->get_record('course_modules',array('id'=>$simplereminder->cmid));
                $module = $DB->get_record('modules',array('id'=>$cm->module));
                $instance = $DB->get_record($module->name,array('id'=>$cm->instance));
                $this->content->text .= '<p>'.get_string('reminderfor','block_simple_reminder').$instance->name.'</p>';
                $this->content->text .= '<p>'.get_string('dateto','block_simple_reminder').'<br>'.userdate($simplereminder->date).'</p>';
            }
        }
        $this->content->text .= '<a href="'.$CFG->wwwroot.'/blocks/simple_reminder/manage.php?course='.$this->page->course->id.'&block='.$this->instance->id.'">'.get_string('managereminder','block_simple_reminder').'</a>';
        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('all' => false,
                     'course-view' => true);
    }

    public function instance_allow_multiple() {
          return true;
    }

    function has_config() {return false;}

    function cron() {
        global $CFG,$DB;
        mtrace('START simple reminder.');
        $simplereminders = $DB->get_records('block_simple_reminder',array('enable'=>1));
        foreach($simplereminders as $simplereminder){
            $course = $DB->get_record('course',array('id'=>$simplereminder->course));
            $cm = $DB->get_record('course_modules',array('id'=>$simplereminder->cmid));
            $module = $DB->get_record('modules',array('id'=>$cm->module));
            if($course->visible && $module){
                mtrace($simplereminder->id.':'.$course->fullname.':'.$module->id);
                if($simplereminder->date < time()){
                    $context = context_course::instance($course->id, MUST_EXIST);
                    $teacher = $DB->get_record('user',array('id'=>$simplereminder->teacher));
                    $users = get_enrolled_users($context);
                    foreach($users as $user){
                        if($simplereminder->completion){
                            require_once(__DIR__.'/../../mod/'.$module->name.'/lib.php');
                            $function = $module->name.'_get_completion_state';
                            if(function_exists($function)){
                                mtrace('Check completion.');
                                $result = $function($course,$cm,$user->id,COMPLETION_AND);
                                if(!$result){
                                    $message = new \core\message\message();
                                    $message->courseid = $simplereminder->course;
                                    $message->component = 'moodle';
                                    $message->name = 'instantmessage';
                                    $message->userfrom = $teacher;
                                    $message->userto = $user;
                                    $message->subject = 'Reminder';
                                    $message->fullmessage = $simplereminder->message;
                                    $message->fullmessageformat = FORMAT_PLAIN;
                                    $message->notification = '0';

                                    $messageid = message_send($message);
                                }
                            }
                        }else{
                            $message = new \core\message\message();
                            $message->courseid = $simplereminder->course;
                            $message->component = 'moodle';
                            $message->name = 'instantmessage';
                            $message->userfrom = $teacher;
                            $message->userto = $user;
                            $message->subject = 'Reminder';
                            $message->fullmessage = $simplereminder->message;
                            $message->fullmessageformat = FORMAT_PLAIN;
                            $message->notification = '0';

                            $messageid = message_send($message);
                        }
                    }
                    $simplereminder->enable = 0;
                    $DB->update_record('block_simple_reminder',$simplereminder);
                }
            }
            mtrace( "Sent simple reminder." );
        }
            return true;
        
    }
}
