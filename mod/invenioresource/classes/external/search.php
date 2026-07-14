<?php

namespace mod_invenioresource\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use context_module;
use external_api;
use external_function_parameters;
use external_value;
use mod_invenioresource\service\search_service;

class search extends external_api
{
    public static function execute(int $cmid, string $keyword)
    {
        self::validate_parameters(
            self::execute_parameters(),
            [
                'keyword' => $keyword
            ]
        );

        global $COURSE;

        if ($cmid > 0) {

            $context = context_module::instance($cmid);

            self::validate_context($context);

            require_capability(
                'mod/invenioresource:selectresource',
                $context
            );

        } else {

            $context = \context_course::instance($COURSE->id);

            self::validate_context($context);

            require_capability(
                'moodle/course:manageactivities',
                $context
            );
        }

        $keywordservice = new \mod_invenioresource\service\keyword_service();

        $keywordservice->increase_keyword($keyword);

        $service = new search_service();

        return json_encode(
            $service->search($keyword)
        );
    }

    public static function execute_parameters()
    {
        return new external_function_parameters([
            'cmid' => new external_value(
                PARAM_INT,
                'Course module id',
                VALUE_DEFAULT,
                0
            ),

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