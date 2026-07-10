<?php

use local_inveniordm\controller\lecturer_controller;

require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../classes/controller/lecturer_controller.php');
require_once(__DIR__ . '/../classes/service/course_service.php');

require_login();

global $PAGE, $OUTPUT;

$context = context_system::instance();

$PAGE->set_url(
    new moodle_url('/local/inveniordm/lecturer/create_course.php')
);

$PAGE->set_context($context);
$PAGE->set_title('Create Course');
$PAGE->set_heading('Create Course');

require_capability('local/inveniordm:upload', $context);

$PAGE->requires->css(
    new moodle_url('/local/inveniordm/styles/main.css')
);

$PAGE->requires->css(
    new moodle_url('/local/inveniordm/styles/create_course.css')
);

$controller = new lecturer_controller();

$data = $controller->get_create_course_context($_POST);

echo $OUTPUT->header();

echo $OUTPUT->render_from_template(
    'local_inveniordm/lecturer/create_course',
    $data
);

echo $OUTPUT->footer();