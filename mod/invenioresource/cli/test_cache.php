<?php

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');

$cache = cache::make(
    'mod_invenioresource',
    'popular_keywords'
);

// ghi cache
$data = [
    'security' => 15,
    'database' => 10,
    'machine learning' => 7,
    'moodle' => 5
];

$cache->set('keywords', $data);

echo "Saved cache\n";


// đọc cache
$result = $cache->get('keywords');

echo "Read cache:\n";

print_r($result);

require_once($CFG->dirroot . '/mod/invenioresource/classes/external/get_popular_keywords.php');

print_r(
    \mod_invenioresource\external\get_popular_keywords::execute()
);