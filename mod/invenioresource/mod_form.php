<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_invenioresource_mod_form extends moodleform_mod
{

    public function definition()
    {
        $mform = $this->_form;

        global $PAGE;

        $PAGE->requires->js_call_amd(
            'mod_invenioresource/resource_picker',
            'init'
        );

        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement(
            'hidden',
            'recordid',
            ''
        );

        $mform->setType(
            'recordid',
            PARAM_TEXT
        );

        $mform->addElement(
            'static',
            'selectedresource',
            get_string('resource', 'mod_invenioresource'),
            get_string('noresourceselected', 'mod_invenioresource')
        );

        $mform->addElement(
            'button',
            'selectresource',
            get_string('selectresource', 'mod_invenioresource')
        );

        $this->standard_intro_elements();

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
}