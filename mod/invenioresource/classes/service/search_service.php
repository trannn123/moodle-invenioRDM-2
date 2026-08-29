<?php

namespace mod_invenioresource\service;

defined('MOODLE_INTERNAL') || die();

use cache;

class search_service
{
    public function search(string $query = ''): array
    {
        $client = new \mod_invenioresource\api\invenio_client();

        $cache = cache::make('mod_invenioresource', 'search_results');

        $key = md5(trim(mb_strtolower($query)));

        error_log("CACHE KEY: " . $key . " QUERY: " . $query);

        // Check Redis cache
        $result = $cache->get($key);

        if ($result !== false) {
            error_log("REDIS CACHE HIT - KEY: " . $key);
            error_log("RETURN RESULT FROM REDIS CACHE");

            return $result;
        }

        // Cache miss -> call InvenioRDM API
        error_log("REDIS CACHE MISS - KEY: " . $key);
        error_log("CALL INVENIO API");

        $result = $client->get_records($query);

        // Save result to Redis cache
        $cache->set($key, $result);

        error_log("REDIS CACHE SET - KEY: " . $key);

        return $result;
    }
}