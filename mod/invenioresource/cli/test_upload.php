<?php

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');

require_once(
    $CFG->dirroot .
    '/mod/invenioresource/classes/api/invenio_client.php'
);

require_once(
    $CFG->dirroot .
    '/mod/invenioresource/classes/service/upload_service.php'
);


$service = new \mod_invenioresource\service\upload_service();


$metadata = [

    "metadata" => [

        "title" => "Test Resource From InvenioResource Module",

        "resource_type" => [
            "id" => "publication"
        ],

        "creators" => [
            [
                "person_or_org" => [
                    "type" => "personal",
                    "given_name" => "Test",
                    "family_name" => "User"
                ]
            ]
        ],

        "publication_date" => "2026-07-11"

    ]

];


$testfile = [
    'name' => 'test-resource.txt',
    'tmp_name' => '/tmp/test-resource.txt'
];


file_put_contents(
    '/tmp/test-resource.txt',
    'This is a test file uploaded from Moodle Invenio Resource module.'
);


$result = $service->upload(
    $metadata,
    $testfile
);


print_r($result);