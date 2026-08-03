<?php

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings->add(
        new admin_setting_configtext(
            'mod_invenioresource/apiurl',
            get_string('apiurl', 'mod_invenioresource'),
            get_string('apiurl_desc', 'mod_invenioresource'),
            'https://host.docker.internal/api',
            PARAM_URL
        )
    );
}