<?php

require('../../config.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('invenioresource', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$instance = $DB->get_record('invenioresource', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);
require_once(
    $CFG->dirroot .
    '/mod/invenioresource/classes/api/invenio_client.php'
);

$client = new \mod_invenioresource\api\invenio_client();

$record = null;

if (!empty($instance->recordid)) {

    $record = $client->get_record(
        $instance->recordid
    );

}

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/invenioresource/view.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title($instance->name);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();

echo $OUTPUT->heading($instance->name);

$renderer = $PAGE->get_renderer('mod_invenioresource');

if ($record) {

    echo $renderer->render_resource($record);

} else {

    echo $OUTPUT->notification(
        'No resource selected.',
        'notifyproblem'
    );

}

echo $OUTPUT->footer();