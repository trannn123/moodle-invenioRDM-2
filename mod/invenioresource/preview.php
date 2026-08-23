<?php

require('../../config.php');

global $DB, $CFG;

$id = required_param('id', PARAM_INT);

$cm = get_coursemodule_from_id(
    'invenioresource',
    $id,
    0,
    false,
    MUST_EXIST
);

$course = get_course($cm->course);

require_login($course, true, $cm);

$instance = $DB->get_record(
    'invenioresource',
    [
        'id' => $cm->instance
    ],
    '*',
    MUST_EXIST
);

require_once(
    $CFG->dirroot .
    '/mod/invenioresource/classes/api/invenio_client.php'
);

$client = new \mod_invenioresource\api\invenio_client();

$record = $client->get_record(
    $instance->recordid
);

if (
    empty($record) ||
    ($record['http_status'] ?? 0) !== 200
) {
    throw new moodle_exception(
        'Resource is not available.'
    );
}

$entries =
    $record['data']['files']['entries']
    ?? [];

if (empty($entries)) {
    throw new moodle_exception(
        'No file is available for this resource.'
    );
}

$file = reset($entries);

$filename = $file['key'] ?? '';
$mimetype = $file['mimetype'] ?? '';

$filecontent = $client->get_file_content(
    $instance->recordid,
    $filename
);

if (
    ($filecontent['http_status'] ?? 0) !== 200 ||
    empty($filecontent['content'])
) {
    throw new moodle_exception(
        'Unable to retrieve file.'
    );
}

if ($mimetype !== 'application/pdf') {
    throw new moodle_exception(
        'This file cannot be previewed. Only PDF files are supported.'
    );
}

header('Content-Type: application/pdf');
header(
    'Content-Disposition: inline; filename="' .
    basename($filename) .
    '"'
);
header(
    'Content-Length: ' .
    strlen($filecontent['content'])
);

echo $filecontent['content'];

exit;