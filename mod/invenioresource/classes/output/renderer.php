<?php

namespace mod_invenioresource\output;

defined('MOODLE_INTERNAL') || die();

class renderer extends \plugin_renderer_base
{
    public function render_resource(array $record): string
    {
        $title =
            $record['metadata']['title']
            ?? 'Untitled';

        $description =
            $record['metadata']['description']
            ?? '';

        $language =
            $record['custom_fields']['moodle:language']
            ?? 'N/A';

        return
            \html_writer::tag('h3', $title) .
            \html_writer::tag('p', $description) .
            \html_writer::tag(
                'p',
                '<strong>Language:</strong> ' . s($language)
            );
    }
}