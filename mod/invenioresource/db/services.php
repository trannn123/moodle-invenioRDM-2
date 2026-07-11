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

];