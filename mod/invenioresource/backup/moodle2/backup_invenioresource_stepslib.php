<?php

defined('MOODLE_INTERNAL') || die();

class backup_invenioresource_activity_structure_step extends backup_activity_structure_step
{

    protected function define_structure()
    {

        $invenioresource = new backup_nested_element('invenioresource', ['id'], [
            'name',
            'intro',
            'introformat',
            'recordid',
            'timecreated',
            'timemodified'
        ]);

        $invenioresource->set_source_table(
            'invenioresource',
            ['id' => backup::VAR_ACTIVITYID]
        );

        return $this->prepare_activity_structure($invenioresource);
    }
}