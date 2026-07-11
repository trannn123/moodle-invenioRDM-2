<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Declare module feature support.
 */
function invenioresource_supports($feature)
{
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;

        case FEATURE_BACKUP_MOODLE2:
            return true;

        default:
            return null;
    }
}