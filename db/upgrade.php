<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_block_simple_reminder_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2019080700) {
        // Add default category
        $table = new xmldb_table('block_simple_reminder');
        $field = new xmldb_field('groupid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'timemodified');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_block_savepoint(true, 2019080700, 'simple_reminder');
    }
    
    if ($oldversion < 2019080702) {
        // Add default category
        $table = new xmldb_table('block_simple_reminder');
        $field = new xmldb_field('groupingid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'timemodified');

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_block_savepoint(true, 2019080702, 'simple_reminder');
    }
    
    return true;
}