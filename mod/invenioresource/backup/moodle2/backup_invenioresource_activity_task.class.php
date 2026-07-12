<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .
    '/mod/invenioresource/backup/moodle2/backup_invenioresource_stepslib.php');

class backup_invenioresource_activity_task extends backup_activity_task
{

    public static function encode_content_links($content)
    {
        return $content;
    }

    protected function define_my_settings()
    {
        // No specific settings.
    }

    protected function define_my_steps()
    {
        $this->add_step(
            new backup_invenioresource_activity_structure_step(
                'invenioresource_structure',
                'invenioresource.xml'
            )
        );
    }
}