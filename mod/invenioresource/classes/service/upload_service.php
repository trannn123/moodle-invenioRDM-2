<?php

namespace mod_invenioresource\service;

defined('MOODLE_INTERNAL') || die();


class upload_service
{

    public function upload(
        array $metadata,
        array $file
    ): array
    {


        $client = new \mod_invenioresource\api\invenio_client();


        // Create draft
        $record = $client->create_record(
            $metadata
        );


        if (empty($record['id'])) {
            return [
                'error' => 'Cannot create record',
                'record' => $record
            ];
        }


        $recordid = $record['id'];


        // Upload file
        $upload = $client->upload_file(
            $recordid,
            $file
        );


        // Publish record
        $publish = $client->publish_record(
            $recordid
        );


        return [
            'recordid' => $recordid,
            'upload' => $upload,
            'publish' => $publish
        ];

    }

}