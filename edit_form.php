<?php

class block_simple_reminder_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        /*
        
        global $DB, $COURSE;
        
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block_simple_reminder'));
        
        $mform->addElement('checkbox', 'config_enablereminder', 'Enable', 'Enable');
        
        $forums = $DB->get_records_menu('forum',array('course'=>$this->page->course->id),'','id,name');
        $mform->addElement('select','config_instance','Target Instance',$forums);
        
        $mform->addElement('textarea', 'config_message', 'Message');
        $mform->setType('config_message', PARAM_TEXT);

        $mform->addElement('date_time_selector', 'config_date', 'Date',array('optional'=>false,'startyear' => 2000, 'stopyear' => date("Y"),'step' => 5));
        
        $group=array();
        $group[] =& $mform->createElement('checkbox', 'config_completionpostsenabled', '', get_string('completionposts','forum'));
        $group[] =& $mform->createElement('text', 'config_completionposts', '', array('size'=>3));
        $mform->setType('config_completionposts',PARAM_INT);
        $mform->addGroup($group, 'config_completionpostsgroup', get_string('completionpostsgroup','forum'), array(' '), false);
        $mform->disabledIf('config_completionposts','config_completionpostsenabled','notchecked');

        $group=array();
        $group[] =& $mform->createElement('checkbox', 'config_completiondiscussionsenabled', '', get_string('completiondiscussions','forum'));
        $group[] =& $mform->createElement('text', 'config_completiondiscussions', '', array('size'=>3));
        $mform->setType('config_completiondiscussions',PARAM_INT);
        $mform->addGroup($group, 'config_completiondiscussionsgroup', get_string('completiondiscussionsgroup','forum'), array(' '), false);
        $mform->disabledIf('config_completiondiscussions','config_completiondiscussionsenabled','notchecked');

        $group=array();
        $group[] =& $mform->createElement('checkbox', 'config_completionrepliesenabled', '', get_string('completionreplies','forum'));
        $group[] =& $mform->createElement('text', 'config_completionreplies', '', array('size'=>3));
        $mform->setType('config_completionreplies',PARAM_INT);
        $mform->addGroup($group, 'config_completionrepliesgroup', get_string('completionrepliesgroup','forum'), array(' '), false);
        $mform->disabledIf('config_completionreplies','config_completionrepliesenabled','notchecked');
        
        $mform->addElement('hidden','config_reminduser',$USER->id);
        
        //$mform->addElement('select','confing_role','Target role',$roles);
        
        //$mform->addElement('select','confing_repeats','Rpeats',$repeats);
        */
    }
}
