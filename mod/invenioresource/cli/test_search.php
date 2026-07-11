<?php

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');

require_once(
    $CFG->dirroot .
    '/mod/invenioresource/classes/api/invenio_client.php'
);

require_once(
    $CFG->dirroot .
    '/mod/invenioresource/classes/service/search_service.php'
);


$service = new \mod_invenioresource\service\search_service();

$result = $service->search('security');

print_r($result);