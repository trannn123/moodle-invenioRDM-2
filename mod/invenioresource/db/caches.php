<?php
defined('MOODLE_INTERNAL') || die();

$definitions = [
    'searchresults' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => true,
    ],

    'popular_keywords' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => true,
    ],
];