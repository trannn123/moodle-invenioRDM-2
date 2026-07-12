<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .
    '/mod/invenioresource/backup/moodle2/restore_invenioresource_stepslib.php');

class restore_invenioresource_activity_task extends restore_activity_task
{

    public static function define_decode_contents()
    {
        return [];
    }

    public static function define_decode_rules()
    {
        return [];
    }

    protected function define_my_settings()
    {
        // No specific settings.
    }

    protected function define_my_steps()
    {
        $this->add_step(
            new restore_invenioresource_activity_structure_step(
                'invenioresource_structure',
                'invenioresource.xml'
            )
        );
    }
}