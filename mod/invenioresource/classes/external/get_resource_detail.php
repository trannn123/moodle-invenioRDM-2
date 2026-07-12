<?php

namespace mod_invenioresource\external;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->libdir . '/externallib.php');

use context_system;
use external_api;
use external_function_parameters;
use external_value;

class get_resource_detail extends external_api
{
    public static function execute(string $recordid)
    {
        self::validate_parameters(
            self::execute_parameters(),
            [
                'recordid' => $recordid
            ]
        );

        self::validate_context(
            context_system::instance()
        );

        global $PAGE;

        $client = new \mod_invenioresource\api\invenio_client();

        $record = $client->get_record($recordid);

        $renderer = $PAGE->get_renderer(
            'mod_invenioresource'
        );

        return $renderer->render_resource($record);
    }

    public static function execute_parameters()
    {
        return new external_function_parameters([
            'recordid' => new external_value(
                PARAM_TEXT,
                'Invenio record id'
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