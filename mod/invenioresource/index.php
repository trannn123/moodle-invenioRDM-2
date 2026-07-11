<?php
require('../../config.php');

$id = required_param('id', PARAM_INT);

$course = get_course($id);

require_login($course);

$PAGE->set_url('/mod/invenioresource/index.php', ['id' => $id]);
$PAGE->set_title(get_string('modulenameplural', 'invenioresource'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('modulenameplural', 'invenioresource'));

echo html_writer::div('Danh sách Invenio Resource sẽ được hiển thị ở đây.');

echo $OUTPUT->footer();