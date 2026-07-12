<?php

defined('MOODLE_INTERNAL') || die();

class restore_invenioresource_activity_structure_step
    extends restore_activity_structure_step
{

    protected function define_structure()
    {

        $paths = [];
        $paths[] = new restore_path_element(
            'invenioresource',
            '/activity/invenioresource'
        );

        return $this->prepare_activity_structure($paths);
    }

    protected function process_invenioresource($data)
    {
        global $DB;

        $data = (object)$data;

        // Gán activity vào khóa học đang restore.
        $data->course = $this->get_courseid();

        // Thêm bản ghi mới.
        $newitemid = $DB->insert_record('invenioresource', $data);

        // Liên kết instance mới với course module.
        $this->apply_activity_instance($newitemid);
    }

    protected function after_execute()
    {
        // Khôi phục file vùng intro nếu có.
        $this->add_related_files('mod_invenioresource', 'intro', null);
    }
}