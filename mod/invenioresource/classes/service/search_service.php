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

        if (($result = $cache->get($key)) !== false) {
            return $result;
        }

        $result = $client->get_records($query);

        $cache->set($key, $result);

        return $result;
    }
}