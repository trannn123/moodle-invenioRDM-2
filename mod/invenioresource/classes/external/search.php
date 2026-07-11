<?php

namespace mod_invenioresource\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use mod_invenioresource\service\search_service;

class search extends external_api
{
    public static function execute(string $keyword)
    {
        self::validate_parameters(
            self::execute_parameters(),
            [
                'keyword' => $keyword
            ]
        );

        $service = new search_service();

        return json_encode(
            $service->search($keyword)
        );
    }

    public static function execute_parameters()
    {
        return new external_function_parameters([
            'keyword' => new external_value(
                PARAM_TEXT,
                'Search keyword'
            )
        ]);
    }

    public static function execute_returns()
    {
        return new external_value(
            PARAM_RAW,
            'Search result'
        );
    }
}