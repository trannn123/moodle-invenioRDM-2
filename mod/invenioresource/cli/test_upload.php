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

    "files" => [
        "enabled" => true
    ],

    "metadata" => [

        "title" => "Test Custom Fields",

        "publication_date" => date('Y-m-d'),

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
        ]
    ],

    "custom_fields" => [

        "moodle:identifier" => "TEST-" . time(),

        "moodle:language" => "English",

        "moodle:documentary_type" => "Book",

        "moodle:format" => "PDF",

        "moodle:location" => "Can Tho",

        "moodle:learning_resource_type" => "Lecture",

        "moodle:target_audience" => "Student",

        "moodle:educational_level" => "University",

        "moodle:induced_activity" => "Reading",

        "moodle:copyright" => "CC-BY",

        "moodle:objective" => "Testing",

        "moodle:taxon_entry" => "Computer Science",

        "moodle:role" => "Author",

        "moodle:entity" => "CTU",

        "moodle:date" => date('Y-m-d'),

        "moodle:relation" => "None",

        "moodle:metadata_accessibility" => "Public",

        "moodle:free_keyword" => [
            "moodle",
            "invenio"
        ]
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