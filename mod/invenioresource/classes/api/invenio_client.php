<?php

namespace mod_invenioresource\api;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->libdir . '/filelib.php');


class invenio_client
{

    private string $apiurl;
    private string $token;


    public function __construct()
    {
        $this->apiurl = get_config(
            'local_inveniordm',
            'apiurl'
        );

        $this->token = get_config(
            'local_inveniordm',
            'apitoken'
        );
    }


    /**
     * Get all records
     */
    public function get_records(
        string $query = ''
    ): array
    {

        $url = $this->apiurl . '/records';


        if (!empty($query)) {

            $words = explode(
                ' ',
                trim($query)
            );

            foreach ($words as &$word) {

                $word .= '*';

            }

            $query = implode(
                ' ',
                $words
            );

            $url .= '?q=' . urlencode($query);
        }

        return $this->request($url);
    }

    private function request(
        string $url
    ): array
    {


        $ch = curl_init();


        curl_setopt_array($ch, [

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,


            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . $this->token
            ],


            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false

        ]);


        $response = curl_exec($ch);


        curl_close($ch);


        return json_decode(
            $response,
            true
        ) ?? [];

    }

    /**
     * Get single record
     */
    public function get_record(
        string $id
    ): array
    {

        $url =
            $this->apiurl .
            '/records/' .
            $id;


        return $this->request($url);
    }

    /**
     * Create draft record
     */
    public function create_record(
        array $metadata
    ): array
    {


        $url =
            $this->apiurl .
            '/records';


        return $this->post(
            $url,
            $metadata
        );
    }

    private function post(
        string $url,
        array  $data
    ): array
    {


        $payload = json_encode($data);


        $ch = curl_init();


        curl_setopt_array($ch, [


            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_POST => true,


            CURLOPT_POSTFIELDS => $payload,


            CURLOPT_HTTPHEADER => [

                'Accept: application/json',

                'Content-Type: application/json',

                'Authorization: Bearer ' . $this->token

            ],


            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false


        ]);


        $response = curl_exec($ch);


        curl_close($ch);


        return json_decode(
            $response,
            true
        ) ?? [];

    }

    /**
     * Upload file
     */
    public function upload_file(
        string $recordid,
        array  $file
    ): array
    {


        $filename = $file['name'];

        $key =
            preg_replace(
                '/[^a-zA-Z0-9._-]/',
                '_',
                $filename
            );


        /*
         * Step 1:
         * create file entry
         */

        $url =
            $this->apiurl .
            "/records/$recordid/draft/files";


        $result = $this->post(
            $url,
            [
                [
                    'key' => $key
                ]
            ]
        );


        if (empty($result)) {
            return [];
        }


        /*
         * Step 2:
         * upload content
         */

        $url =
            $this->apiurl .
            "/records/$recordid/draft/files/$key/content";


        $content =
            file_get_contents(
                $file['tmp_name']
            );


        $ch = curl_init();


        curl_setopt_array($ch, [

            CURLOPT_URL => $url,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_CUSTOMREQUEST => 'PUT',

            CURLOPT_POSTFIELDS => $content,


            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/octet-stream'
            ],


            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false

        ]);


        $response = curl_exec($ch);

        $code = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );


        curl_close($ch);


        /*
 * Step 3:
 * Commit file
 */

        $commit_url =
            $this->apiurl .
            "/records/$recordid/draft/files/$key/commit";


        $commit = $this->post(
            $commit_url,
            []
        );


        return [
            'code' => $code,
            'response' => $response,
            'commit' => $commit
        ];

    }

    /**
     * Publish record
     */
    public function publish_record(
        string $id
    ): array
    {


        $url =
            $this->apiurl .
            "/records/$id/draft/actions/publish";


        return $this->post(
            $url,
            []
        );

    }

}