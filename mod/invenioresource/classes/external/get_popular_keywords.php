<?php

namespace mod_invenioresource\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_value;

class get_popular_keywords extends external_api
{
    public static function execute_returns()
    {
        return new external_multiple_structure(
            new external_value(PARAM_TEXT)
        );
    }

    public static function execute()
    {
        global $USER;

        self::validate_parameters(
            self::execute_parameters(),
            []
        );

        $cache = \cache::make(
            'mod_invenioresource',
            'popular_keywords'
        );

        // Cache riêng cho từng người dùng.
        $cachekey = 'user_' . $USER->id;

        $keywords = $cache->get($cachekey);

        if ($keywords === false) {
            $keywords = [];
        }

        arsort($keywords);

        $keywords = array_keys($keywords);

        $keywords = array_slice($keywords, 0, 10);

        return $keywords;
    }

    public static function execute_parameters()
    {
        return new external_function_parameters([]);
    }
}