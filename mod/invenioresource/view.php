<?php

require('../../config.php');

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id('invenioresource', $id, 0, false, MUST_EXIST);
$course = get_course($cm->course);
$instance = $DB->get_record('invenioresource', ['id' => $cm->instance], '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/invenioresource/view.php', ['id' => $cm->id]);
$PAGE->set_context($context);
$PAGE->set_title($instance->name);
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

echo $OUTPUT->heading($instance->name);

echo html_writer::div('Hello Invenio Resource');

echo $OUTPUT->footer();