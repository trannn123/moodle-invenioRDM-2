<?php
function xmldb_invenioresource_upgrade($oldversion)
{

    global $DB;

    if ($oldversion < 2026071501) {

        $table =
            new xmldb_table('invenioresource');

        $field =
            new xmldb_field(
                'userid',
                XMLDB_TYPE_INTEGER,
                '10',
                null,
                XMLDB_NOTNULL,
                null,
                0,
                'course'
            );

        $dbman = $DB->get_manager();

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        upgrade_mod_savepoint(
            true,
            2026071501,
            'invenioresource'
        );
    }


    if ($oldversion < 2026072000) {

        $table = new xmldb_table('invenioresource');

        $field = new xmldb_field(
            'overview',
            XMLDB_TYPE_TEXT,
            null,
            null,
            null,
            null,
            null,
            'introformat'
        );

        $dbman = $DB->get_manager();

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(
            true,
            2026072000,
            'invenioresource'
        );
    }

    if ($oldversion < 2026072001) {

        $table = new xmldb_table('invenioresource');

        $field = new xmldb_field(
            'lessoncontent',
            XMLDB_TYPE_TEXT,
            null,
            null,
            null,
            null,
            null,
            'overview'
        );

        $dbman = $DB->get_manager();

        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_mod_savepoint(
            true,
            2026072001,
            'invenioresource'
        );
    }

    if ($oldversion < 2026072100) {

        $table = new xmldb_table('invenioresource');

        $field = new xmldb_field('lessoncontent');

        $dbman = $DB->get_manager();

        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        upgrade_mod_savepoint(
            true,
            2026072100,
            'invenioresource'
        );
    }

    return true;
}