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

        $description = $metadata['description'] ?? '';

        $downloadurl = '';

        if (!empty($record['files']['entries'])) {

            $file = reset($record['files']['entries']);

            $downloadurl = $file['links']['content'] ?? '';
        }

        $data = [

            // Basic metadata
            'title' => $title,
            'description' => $description,
            'downloadurl' => $downloadurl,

            // LOM custom metadata
            'language' =>
                $customfields['moodle:language'] ?? 'N/A',

            'keywords' =>
                $customfields['moodle:free_keyword'] ?? [],

            'format' =>
                $customfields['moodle:format'] ?? '',

            'documentarytype' =>
                $customfields['moodle:documentary_type'] ?? '',

            'learningresourcetype' =>
                $customfields['moodle:learning_resource_type'] ?? '',

            'targetaudience' =>
                $customfields['moodle:target_audience'] ?? '',

            'educationallevel' =>
                $customfields['moodle:educational_level'] ?? '',

            'objective' =>
                $customfields['moodle:objective'] ?? '',

            'taxonentry' =>
                $customfields['moodle:taxon_entry'] ?? '',

            'entity' =>
                $customfields['moodle:entity'] ?? '',

            'copyright' =>
                $customfields['moodle:copyright'] ?? '',

            'identifier' =>
                $customfields['moodle:identifier'] ?? '',

            'role' =>
                $customfields['moodle:role'] ?? '',

            'date' =>
                $customfields['moodle:date'] ?? '',

            'relation' =>
                $customfields['moodle:relation'] ?? '',

            'location' =>
                $customfields['moodle:location'] ?? '',

            'inducedactivity' =>
                $customfields['moodle:induced_activity'] ?? '',

            'metadataaccessibility' =>
                $customfields['moodle:metadata_accessibility'] ?? '',
        ];

        return $this->render_from_template(
            'mod_invenioresource/resource',
            $data
        );
    }
}