<?php

namespace mod_invenioresource\external;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/course/lib.php');

use context_system;
use external_api;
use external_function_parameters;
use external_value;

class get_resource_detail extends external_api
{
    public static function execute(string $recordid, int $cmid = 0)
    {
        self::validate_parameters(
            self::execute_parameters(),
            [
                'recordid' => $recordid,
                'cmid' => $cmid
            ]
        );

        self::validate_context(
            context_system::instance()
        );

        global $PAGE;

        $client = new \mod_invenioresource\api\invenio_client();

        $record = $client->get_record($recordid);

        if (
            empty($record) ||
            !isset($record['http_status']) ||
            $record['http_status'] !== 200
        ) {

            global $DB, $USER;

            $cm = get_coursemodule_from_id(
                'invenioresource',
                $cmid,
                0,
                false,
                MUST_EXIST
            );

            $resource = $DB->get_record(
                'invenioresource',
                [
                    'id' => $cm->instance
                ],
                'id, userid',
                MUST_EXIST
            );

            $canreplace = false;
            error_log("resourceid truoc" . (int)$resource->userid);
            error_log("userid truoc" . (int)$USER->id);
            if ($resource) {
                error_log("resourceid" . (int)$resource->userid);
                error_log("userid" . (int)$USER->id);
                $canreplace =
                    ((int)$resource->userid === (int)$USER->id);

            }


            return json_encode([
                'success' => false,

                'status' =>
                    $record['http_status'],

                'message' =>
                    'Tài nguyên hiện không còn khả dụng hoặc đã bị giới hạn quyền truy cập.',

                'canreplace' =>
                    $canreplace
            ]);

        }

        $renderer = $PAGE->get_renderer(
            'mod_invenioresource'
        );

        return json_encode([
            'success' => true,
            'html' => $renderer->render_resource(
                $record['data']
            )
        ]);
    }

    public static function execute_parameters()
    {
        return new external_function_parameters([

            'recordid' => new external_value(
                PARAM_TEXT,
                'Invenio record id'
            ),

            'cmid' => new external_value(
                PARAM_INT,
                'Course module id',
                VALUE_DEFAULT,
                0
            )
        ]);
    }

    public static function execute_returns()
    {
        return new external_value(
            PARAM_RAW,
            'Rendered resource HTML'
        );
    }
}