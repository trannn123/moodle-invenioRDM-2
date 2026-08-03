<?php
defined('MOODLE_INTERNAL') || die();

$definitions = [
    'search_results' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => true,
        'ttl' => 300,
    ],

    'popular_keywords' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => true,
    ],
];