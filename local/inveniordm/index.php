<?php

require_once(__DIR__ . '/../../config.php');

use local_inveniordm\controller\dashboard_controller;

$controller = new dashboard_controller();
$controller->index();