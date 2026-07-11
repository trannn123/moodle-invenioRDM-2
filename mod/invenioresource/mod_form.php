<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_invenioresource_mod_form extends moodleform_mod
{

    public function definition()
    {
        $mform = $this->_form;

        global $CFG;

        require_once(
            $CFG->dirroot .
            '/mod/invenioresource/classes/api/invenio_client.php'
        );

        $client = new \mod_invenioresource\api\invenio_client();

        $records = $client->get_records();

        $options = [
            '' => 'Select resource'
        ];

        if (!empty($records['hits']['hits'])) {

            foreach ($records['hits']['hits'] as $hit) {

                $options[$hit['id']] =
                    $hit['metadata']['title']
                    ?? $hit['id'];

            }
        }

        $mform->addElement('text', 'name', get_string('name'));
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement(
            'select',
            'recordid',
            'Invenio Resource',
            $options
        );

        $mform->setType(
            'recordid',
            PARAM_TEXT
        );

        $this->standard_intro_elements();

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }
}