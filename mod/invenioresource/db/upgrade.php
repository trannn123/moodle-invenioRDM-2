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

    if ($oldversion < 2026072200) {

        $dbman = $DB->get_manager();

        $table = new xmldb_table('invenioresource_activity');

        // Fields.
        $table->add_field(
            'id',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            XMLDB_NOTNULL,
            XMLDB_SEQUENCE
        );

        $table->add_field(
            'invenioresourceid',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            XMLDB_NOTNULL,
            null,
            0
        );

        $table->add_field(
            'cmid',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            XMLDB_NOTNULL,
            null,
            0
        );

        $table->add_field(
            'activitytype',
            XMLDB_TYPE_CHAR,
            '30',
            null,
            XMLDB_NOTNULL
        );

        // Keys.
        $table->add_key(
            'primary',
            XMLDB_KEY_PRIMARY,
            ['id']
        );

        // Indexes.
        $table->add_index(
            'lesson',
            XMLDB_INDEX_NOTUNIQUE,
            ['invenioresourceid']
        );

        $table->add_index(
            'cmid',
            XMLDB_INDEX_UNIQUE,
            ['cmid']
        );

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_mod_savepoint(
            true,
            2026072200,
            'invenioresource'
        );
    }

    if ($oldversion < 2026072201) {

        $dbman = $DB->get_manager();

        $table = new xmldb_table('invenioresource_activity');

        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        upgrade_mod_savepoint(
            true,
            2026072201,
            'invenioresource'
        );
    }

    if ($oldversion < 2026072202) {

        $dbman = $DB->get_manager();

        $table = new xmldb_table('invenioresource');

        $field = new xmldb_field('overview');

        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        upgrade_mod_savepoint(
            true,
            2026072202,
            'invenioresource'
        );
    }
    return true;
}