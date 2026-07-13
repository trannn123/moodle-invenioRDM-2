<?php

defined('MOODLE_INTERNAL') || die();

$functions = [

    'mod_invenioresource_search' => [

        'classname' =>
            'mod_invenioresource\external\search',

        'methodname' =>
            'execute',

        'description' =>
            'Search Invenio resources',

        'type' =>
            'read',

        'ajax' =>
            true,

    ],

    'mod_invenioresource_get_resource_detail' => [

        'classname' =>
            'mod_invenioresource\external\get_resource_detail',

        'methodname' =>
            'execute',

        'description' =>
            'Get Invenio resource detail',

        'type' =>
            'read',

        'ajax' =>
            true,

    ],

    'mod_invenioresource_get_popular_keywords' => [

        'classname' =>
            'mod_invenioresource\external\get_popular_keywords',

        'methodname' =>
            'execute',

        'description' =>
            'Get popular search keywords',

        'type' =>
            'read',

        'ajax' =>
            true,

    ],
];