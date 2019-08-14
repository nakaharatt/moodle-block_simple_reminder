<?php
/**
 * @package    block_simple_reminder
 * @copyright  Takahiro Nakahara <nakahara@3strings.co.jp>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("$CFG->libdir/formslib.php");

class block_simple_reminder_manage_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB,$COURSE,$USER;
        $mform = $this->_form;
        
        global $DB, $COURSE;
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block_simple_reminder'));
        
        $mform->addElement('checkbox', 'enable', get_string('enable', 'block_simple_reminder'), get_string('enableinfo', 'block_simple_reminder'));
        $modinfo = get_fast_modinfo($COURSE);
        $groups = groups_get_all_groups($COURSE->id);
        $groupings = groups_get_all_groupings($COURSE->id);
        $options = array();
        foreach($modinfo->cms as $cm){
            $modname = get_string('pluginname',$cm->modname);
            $options[$cm->id] = $modname.' : '.$cm->name;
        }
        $mform->addElement('select','cmid',get_string('targetinstance', 'block_simple_reminder'),$options);
        
        $mform->addElement('textarea', 'message', get_string('message', 'block_simple_reminder'));
        $mform->setType('message', PARAM_TEXT);

        $mform->addElement('date_time_selector', 'date', get_string('date', 'block_simple_reminder'),array('optional'=>false,'startyear' => date("Y")-5, 'stopyear' => date("Y")+5,'step' => 5));
        
        $mform->addElement('checkbox', 'completion', get_string('completion', 'block_simple_reminder'), get_string('usecompletion', 'block_simple_reminder'));
        
        $options = array(0=>get_string('none'));
        foreach($groups as $group){
            $options[$group->id] = $group->name;
        }
        $mform->addElement('select','groupid',get_string('group'),$options);
        
        $options = array(0=>get_string('none'));
        foreach($groupings as $grouping){
            $options[$grouping->id] = $grouping->name;
        }
        $mform->addElement('select','groupingid',get_string('grouping','group'),$options);
        
        
        $mform->addElement('hidden','teacher',$USER->id);
        $mform->setType('teacher', PARAM_INT);
        
        $mform->addElement('hidden', 'course', $COURSE->id);
        $mform->setType('course',PARAM_INT);
        
        $mform->addElement('hidden', 'block', $this->_customdata['block']);
        $mform->setType('block',PARAM_INT);
        
        //$mform->addElement('select','confing_role','Target role',$roles);
        
        //$mform->addElement('select','confing_repeats','Rpeats',$repeats);
        
        $this->add_action_buttons();
    }
}
