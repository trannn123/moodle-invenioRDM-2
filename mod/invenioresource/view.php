<?php

require('../../config.php');

global $DB, $OUTPUT, $CFG, $PAGE;
$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('invenioresource', $id, 0, false, MUST_EXIST);

$course = get_course($cm->course);
$instance = $DB->get_record('invenioresource', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

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

$PAGE->requires->js_call_amd(
    'mod_invenioresource/resource_detail',
    'init',
    [
        $cm->id
    ]
);

$PAGE->set_title($instance->name);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();

echo '<div class="card mb-4">';
echo '<div class="card-header">';
echo '<h4 class="mb-0">Learning Resource</h4>';
echo '</div>';

echo '<div class="card-body">';
if ($record) {

    echo html_writer::tag(
        'button',
        'Xem chi tiết tài nguyên',
        [
            'class' => 'btn btn-primary mt-3 mb-3',
            'id' => 'view-resource-detail',
            'data-recordid' => $instance->recordid
        ]
    );

} else {

    echo $OUTPUT->notification(
        'No resource selected.',
        'notifyproblem'
    );

}
echo '</div>';
echo '</div>';
echo $OUTPUT->footer();