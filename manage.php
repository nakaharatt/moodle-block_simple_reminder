<?php
/**
 * @package    block_simple_reminder
 * @copyright  Takahiro Nakahara <nakahara@3strings.co.jp>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../config.php');
require_once('manage_form.php');

$courseid = required_param('course', PARAM_INT);
$blockid = required_param('block', PARAM_INT);

$params['course'] = $courseid;
$params['block'] = $blockid;
$course = $DB->get_record('course',array('id'=>$courseid));
$returnurl = new moodle_url('/course/view.php', array('id' => $courseid));
require_course_login($course);
$coursecontext = context_course::instance($course->id);

require_capability('block/simple_reminder:manage', $coursecontext);

$simplereminder = $DB->get_record('block_simple_reminder',array('block'=>$blockid));
$mform = new block_simple_reminder_manage_form(null,array('block'=>$blockid));

/*
echo "<pre>";
$users = get_enrolled_users($coursecontext);
var_dump($users);
die;
*/

if($mform->is_cancelled()){
    //Back to course.
    redirect($returnurl);
}elseif($fromform = $mform->get_data()){
    if($simplereminder){
        $fromform->id = $simplereminder->id;
        $fromform->timemodified = time();
        $DB->update_record('block_simple_reminder',$fromform);
        
    }else{
        $fromform->timecreated = time();
        $fromform->timemodified = time();
        $DB->insert_record('block_simple_reminder',$fromform);
    }
    redirect($returnurl);
}else{
    $PAGE->set_pagelayout('incourse');
    $PAGE->set_url($CFG->wwwroot.'/blocks/simple_reminder/manage.php?course='.$courseid.'&block='.$blockid);
    $PAGE->navbar->add('Simple reminder');
    $PAGE->set_heading($course->fullname);
    echo $OUTPUT->header();
    $mform->set_data($simplereminder);
    $mform->display();

    echo $OUTPUT->footer();
}