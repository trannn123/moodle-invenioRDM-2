<?php

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');

$r = new ReflectionClass(\mod_invenioresource\output\renderer::class);

echo $r->getFileName() . PHP_EOL;