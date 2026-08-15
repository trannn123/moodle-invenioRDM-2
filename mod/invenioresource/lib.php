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

        case FEATURE_SHOW_DESCRIPTION:
            return true;

        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;

        case FEATURE_BACKUP_MOODLE2:
            return true;

        default:
            return null;
    }
}

/**
 * Add a new Invenio Resource instance.
 */
function invenioresource_add_instance($data)
{
    global $DB, $USER;

    $data->timecreated = time();
    $data->timemodified = time();

    $data->userid = $USER->id;

    // Tra ve ID cua record vua duoc tao
    return $DB->insert_record(
        'invenioresource',
        $data
    );
}


/**
 * Update an existing Invenio Resource instance.
 */
function invenioresource_update_instance($data)
{
    global $DB;

    $data->timemodified = time();

    // Gan ID la ID cua instance dang duoc chinh sua
    $data->id = $data->instance;

    $old = $DB->get_record(
        'invenioresource',
        ['id' => $data->id],
        'userid',
        MUST_EXIST
    );

    $data->userid = $old->userid;

    return $DB->update_record(
        'invenioresource',
        $data
    );
}


/**
 * Delete an Invenio Resource instance.
 */
function invenioresource_delete_instance($id)
{
    global $DB;

    return $DB->delete_records(
        'invenioresource',
        ['id' => $id]
    );
}