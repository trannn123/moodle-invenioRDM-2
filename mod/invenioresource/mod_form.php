<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/course/moodleform_mod.php');

class mod_invenioresource_mod_form extends moodleform_mod
{

    public function definition()
    {
        $mform = $this->_form;

        global $PAGE, $OUTPUT, $CFG;

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

        $selectedtitle = get_string(
            'noresourceselected',
            'invenioresource'
        );

        if (!empty($this->_instance)) {

            global $DB;

            $record = $DB->get_record(
                'invenioresource',
                ['id' => $this->_instance]
            );

            if ($record && !empty($record->recordid)) {

                require_once(
                    $CFG->dirroot .
                    '/mod/invenioresource/classes/api/invenio_client.php'
                );

                $client = new \mod_invenioresource\api\invenio_client();

                $inveniorecord = $client->get_record(
                    $record->recordid
                );

                if (!empty($inveniorecord['metadata']['title'])) {

                    $selectedtitle =
                        $inveniorecord['metadata']['title'];

                }
            }
        }

        $mform->addElement(
            'html',
            '
            <div class="form-group row">
                <div class="col-md-3">
                    <label class="col-form-label">
                        ' . get_string(
                'resource',
                'invenioresource'
            ) . '
                    </label>
                </div>
        
                <div class="col-md-9">
                    <span id="selected-resource-name">'
            . $selectedtitle .
            '</span>
                </div>
            </div>
            '
        );

        $mform->addElement(
            'button',
            'selectresource',
            get_string('selectresource', 'mod_invenioresource')
        );

        $mform->addElement(
            'button',
            'clearresource',
            get_string(
                'clearresource',
                'invenioresource'
            )
        );

        $this->standard_intro_elements();

        $this->standard_coursemodule_elements();

        $this->add_action_buttons();
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        if (empty($data['recordid'])) {

            $errors['selectresource'] =
                'Please select an Invenio resource.';

        }
        return $errors;
    }
}