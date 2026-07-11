<?php


namespace mod_invenioresource\service;

defined('MOODLE_INTERNAL') || die();

class search_service
{
    public function search(string $query = ''): array
    {
        $client = new \mod_invenioresource\api\invenio_client();

        return $client->get_records($query);
    }
}