<?php

namespace mod_invenioresource\output;

defined('MOODLE_INTERNAL') || die();

class renderer extends \plugin_renderer_base
{
    public function render_resource(array $record): string
    {
        $metadata = $record['metadata'] ?? [];
        $customfields = $record['custom_fields'] ?? [];

        $title = $metadata['title'] ?? 'Untitled';

        $description =
            $metadata['description']
            ?? 'No description available.';

        $downloadurl = '';
        $filename = '';
        $filesize = '';

        if (!empty($record['files']['entries'])) {

            $file = reset($record['files']['entries']);

            $downloadurl =
                $file['links']['content'] ?? '';

            $filename =
                $file['key'] ?? '';

            $filesize = '';

            if (!empty($file['size'])) {

                $filesize =
                    display_size(
                        $file['size']
                    );

            }
        }

        $data = [

            // Basic metadata
            'title' => $title,
            'description' => $description,
            'downloadurl' => $downloadurl,

            'filename' =>
                $this->display_value($filename),

            'filesize' =>
                $this->display_value($filesize),

            // LOM custom metadata
            'language' =>
                $customfields['moodle:language'] ?? 'N/A',

            'keywords' =>
                is_array(
                    $customfields['moodle:free_keyword'] ?? null
                )
                    ? $customfields['moodle:free_keyword']
                    : [],

            'format' =>
                $this->display_value(
                    $customfields['moodle:format'] ?? null
                ),

            'documentarytype' =>
                $this->display_value(
                    $customfields['moodle:documentary_type'] ?? null
                ),

            'learningresourcetype' =>
                $this->display_value(
                    $customfields['moodle:learning_resource_type'] ?? null
                ),

            'targetaudience' =>
                $this->display_value(
                    $customfields['moodle:target_audience'] ?? null
                ),

            'educationallevel' =>
                $this->display_value(
                    $customfields['moodle:educational_level'] ?? null
                ),

            'objective' =>
                $this->display_value(
                    $customfields['moodle:objective'] ?? null
                ),

            'taxonentry' =>
                $this->display_value(
                    $customfields['moodle:taxon_entry'] ?? null
                ),

            'entity' =>
                $this->display_value(
                    $customfields['moodle:entity'] ?? null
                ),

            'copyright' =>
                $this->display_value(
                    $customfields['moodle:copyright'] ?? null
                ),

            'identifier' =>
                $this->display_value(
                    $customfields['moodle:identifier'] ?? null
                ),

            'role' =>
                $this->display_value(
                    $customfields['moodle:role'] ?? null
                ),

            'date' =>
                $this->display_value(
                    $customfields['moodle:date'] ?? null
                ),

            'relation' =>
                $this->display_value(
                    $customfields['moodle:relation'] ?? null
                ),

            'location' =>
                $this->display_value(
                    $customfields['moodle:location'] ?? null
                ),

            'inducedactivity' =>
                $this->display_value(
                    $customfields['moodle:induced_activity'] ?? null
                ),

            'metadataaccessibility' =>
                $this->display_value(
                    $customfields['moodle:metadata_accessibility'] ?? null
                ),
        ];

        return $this->render_from_template(
            'mod_invenioresource/resource',
            $data
        );
    }

    private function display_value($value): string
    {
        if (is_array($value)) {

            return empty($value)
                ? 'Not specified'
                : implode(', ', $value);

        }

        return !empty($value)
            ? $value
            : 'Not specified';
    }
}