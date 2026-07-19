<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    $settings = new admin_settingpage(
        'mod_invenioresource',
        get_string('pluginname', 'mod_invenioresource')
    );

    $settings->add(new admin_setting_configtext(
        'mod_invenioresource/apiurl',
        'API URL',
        'InvenioRDM REST API URL',
        'https://host.docker.internal/api'
    ));

    $ADMIN->add('modsettings', $settings);
}