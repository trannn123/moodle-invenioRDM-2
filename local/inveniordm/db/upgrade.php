<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_inveniordm_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026062202) {

        $table = new xmldb_table('local_inveniordm_assignments');

        // Xóa recordid.
        $field = new xmldb_field('recordid');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Xóa resource_recordid.
        $field = new xmldb_field('resource_recordid');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Đổi description -> instructions.
        $description = new xmldb_field('description');

        if ($dbman->field_exists($table, $description)) {
            $dbman->drop_field($table, $description);
        }

        $instructions = new xmldb_field(
            'instructions',
            XMLDB_TYPE_TEXT,
            null,
            null,
            null,
            null
        );

        if (!$dbman->field_exists($table, $instructions)) {
            $dbman->add_field($table, $instructions);
        }

        // Tạo bảng assignment_resources.
        $table2 = new xmldb_table(
            'local_inveniordm_assignment_resources'
        );

        if (!$dbman->table_exists($table2)) {

            $table2->add_field(
                'id',
                XMLDB_TYPE_INTEGER,
                '10',
                null,
                XMLDB_NOTNULL,
                XMLDB_SEQUENCE,
                null
            );

            $table2->add_field(
                'assignmentid',
                XMLDB_TYPE_INTEGER,
                '10',
                null,
                XMLDB_NOTNULL
            );

            $table2->add_field(
                'recordid',
                XMLDB_TYPE_CHAR,
                '100',
                null,
                XMLDB_NOTNULL
            );

            $table2->add_field(
                'title',
                XMLDB_TYPE_CHAR,
                '255',
                null,
                XMLDB_NOTNULL
            );

            $table2->add_key(
                'primary',
                XMLDB_KEY_PRIMARY,
                ['id']
            );

            $dbman->create_table($table2);
        }

        upgrade_plugin_savepoint(
            true,
            2026062202,
            'local',
            'inveniordm'
        );
    }


    if ($oldversion < 2026062204) {


        $table = new xmldb_table('local_inveniordm_course_resources');

        if (!$dbman->table_exists($table)) {

            $table->add_field(
                'id',
                XMLDB_TYPE_INTEGER,
                '10',
                null,
                XMLDB_NOTNULL,
                XMLDB_SEQUENCE
            );

            $table->add_field(
                'courseid',
                XMLDB_TYPE_INTEGER,
                '10',
                null,
                XMLDB_NOTNULL
            );

            $table->add_field(
                'recordid',
                XMLDB_TYPE_CHAR,
                '100',
                null,
                XMLDB_NOTNULL
            );

            $table->add_field(
                'title',
                XMLDB_TYPE_CHAR,
                '255',
                null,
                XMLDB_NOTNULL
            );

            $table->add_field(
                'timecreated',
                XMLDB_TYPE_INTEGER,
                '10',
                null,
                XMLDB_NOTNULL
            );

            $table->add_key(
                'primary',
                XMLDB_KEY_PRIMARY,
                ['id']
            );

            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(
            true,
            2026062204,
            'local',
            'inveniordm'
        );
    }

    return true;
}
