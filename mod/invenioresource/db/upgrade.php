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

        if (!$dbman->field_exists($table, 'userid')) {

            $dbman->add_field(
                $table,
                $field
            );
        }


        upgrade_mod_savepoint(
            true,
            2026071501,
            'invenioresource'
        );
    }


    return true;
}