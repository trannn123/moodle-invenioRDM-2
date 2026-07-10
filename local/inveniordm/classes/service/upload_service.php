<?php

namespace local_inveniordm\service;

use context_user;

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once(
    $CFG->dirroot .
    '/local/inveniordm/classes/api/invenio_client.php'
);

require_once(
    $CFG->dirroot .
    '/local/inveniordm/classes/service/invenio_mapper.php'
);

require_once(
    $CFG->dirroot .
    '/local/inveniordm/classes/service/log_service.php'
);

class upload_service
{
    public function upload($data, $user): array
    {
        global $CFG;

        $result = [
            'success' => false,
            'message' => '',
            'recordid' => null
        ];

        $fs = get_file_storage();
        $usercontext = context_user::instance($user->id);

        $files = $fs->get_area_files(
            $usercontext->id,
            'user',
            'draft',
            $data->resourcefile,
            'id',
            false
        );

        $filepath = '';
        $filename = '';

        foreach ($files as $file) {
            $filename = $file->get_filename();

            $fullpath =
                $CFG->dirroot .
                '/local/inveniordm/repository/' .
                time() .
                '_' .
                $filename;

//            $file->copy_content_to($fullpath);
//            error_log("Copied file: " . $fullpath);
//            error_log("File exists? " . (file_exists($fullpath) ? "YES" : "NO"));
//            $filepath = $fullpath;
//            break;
            error_log("FULLPATH = " . $fullpath);

            if (!is_dir(dirname($fullpath))) {
                error_log("Repository directory DOES NOT EXIST");
            } else {
                error_log("Repository directory EXISTS");
            }
            try {
                error_log("Stored filename: " . $file->get_filename());
                error_log("Stored filesize: " . $file->get_filesize());
                error_log("Contenthash: " . $file->get_contenthash());
                error_log("Is directory: " . ($file->is_directory() ? "YES" : "NO"));
                $resultcopy = $file->copy_content_to($fullpath);
                error_log("copy returned: " . var_export($resultcopy, true));
            } catch (\Throwable $e) {
                error_log("copy exception: " . $e->getMessage());
            }

            error_log("copy_content_to returned: " . var_export($resultcopy, true));
            error_log("Destination: " . $fullpath);
            error_log("Directory exists: " . (is_dir(dirname($fullpath)) ? "YES" : "NO"));
            error_log("File exists: " . (file_exists($fullpath) ? "YES" : "NO"));
            error_log("Directory writable: " . (is_writable(dirname($fullpath)) ? "YES" : "NO"));

            $filepath = $fullpath;
            break;
        }

        if (empty($filepath)) {
            $result['message'] = 'No uploaded file found';
            return $result;
        }

        $client = new \local_inveniordm\api\invenio_client();

        $recordpayload =
            \invenio_mapper::map(
                $data,
                $user
            );


        $record = $client->create_record(
            $recordpayload
        );
        error_log('Create record: ' . print_r($record, true));
        $recordid = $record['data']['id'] ?? null;
        error_log("Before upload_file()");
        error_log("Path: " . $filepath);
        error_log("Exists: " . (file_exists($filepath) ? "YES" : "NO"));
        $uploadresult = $client->upload_file(
            $recordid,
            [
                'name' => $filename,
                'tmp_name' => $filepath
            ]
        );

        $publishresult =
            $client->publish_record(
                $recordid
            );

        $publishcode =
            $publishresult['httpcode'];
        error_log('Publish result: ' . print_r($publishresult, true));
        if ($publishcode >= 200 && $publishcode < 300) {

            log_service::add(
                $user->id,
                'UPLOAD_RESOURCE',
                $recordid
            );

            $result['success'] = true;
            $result['message'] = 'Upload resource successfully!';
            $result['recordid'] = $recordid;

            return $result;
        }

        $result['message'] = 'Upload or publish failed!';

        return $result;
    }
}