<?php

namespace local_inveniordm\controller;

defined('MOODLE_INTERNAL') || die();

use local_inveniordm\service\assignment_service;
use local_inveniordm\service\course_service;
use local_inveniordm\service\resource_service;
use local_inveniordm\service\submission_service;
use local_inveniordm\service\upload_service;
use moodle_url;

class lecturer_controller
{
    public function get_all_assignments_context(): array
    {
        global $USER;
        $search = optional_param('search', '', PARAM_TEXT);
        $search = trim($search);
        $page = optional_param('page', 1, PARAM_INT);
        $courses = enrol_get_my_courses();
        $service = new assignment_service();

        $assignments = $service->get_lecturer_all_assignments(
            $USER->id,
            $courses,
            $search,
            $page
        );

        return [
            'assignments' => $assignments['items'],
            'pagination' => $assignments['pagination'],
            'totalassignments' => $assignments['totalassignments'],
            'totalcourses' => $assignments['totalcourses'],
            'search' => $search,
            'backurl' => (new moodle_url('/local/inveniordm/index.php'))->out(false),
            'reseturl' => (new moodle_url('/local/inveniordm/lecturer/all_assignments.php'))->out(false),
            'hasassignments' => !empty($assignments['items']),
        ];
    }

    public function get_course_assignments_context(): array
    {
        global $USER;
        $courseid = required_param('courseid', PARAM_INT);
        $search = trim(optional_param('search', '', PARAM_TEXT));
        $page = optional_param('page', 1, PARAM_INT);
        $service = new assignment_service();

        $data = $service->get_lecturer_assignments(
            $courseid,
            $USER->id,
            $search,
            $page
        );

        return array_merge($data, [
            'courseid' => $courseid,
            'search' => $search,
            'backurl' => (new moodle_url('/local/inveniordm/lecturer/my_courses.php'))->out(false),
            'reseturl' => (new moodle_url('/local/inveniordm/lecturer/assignments.php', ['courseid' => $courseid]))->out(false),
            'createurl' => (new moodle_url('/local/inveniordm/lecturer/create_assignment.php', ['courseid' => $courseid]))->out(false),
        ]);
    }

    public function get_course_resources_context(int $courseid): array
    {
        $service = new resource_service();
        $page = optional_param('page', 1, PARAM_INT);
        $data = $service->get_lecturer_course_resources($courseid, $page);

        return array_merge($data, [
            'courseid' => $courseid,
            'backurl' => (new moodle_url(
                '/local/inveniordm/lecturer/my_courses.php'
            ))->out(false),
            'searchurl' => (new moodle_url(
                '/local/inveniordm/lecturer/search_resources_to_attach.php',
                ['courseid' => $courseid]
            ))->out(false),
        ]);
    }

    public function get_create_assignment_context(int $courseid, array $post): array
    {
        $service = new assignment_service();
        if (!empty($post)) {
            return $service->create_assignment($courseid, $post);
        }

        return $service->get_create_assignment_form_context($courseid);
    }

    public function get_my_courses_context(): array
    {
        global $USER;
        $search = optional_param('search', '', PARAM_TEXT);
        $page = optional_param('page', 1, PARAM_INT);
        $search = trim($search);

        $service = new course_service();
        $data = $service->get_lecturer_my_courses(
            $USER->id,
            $search,
            $page
        );

        return array_merge($data, [
            'search' => $search,
            'backurl' => (new moodle_url(
                '/local/inveniordm/index.php'
            ))->out(false),
            'reseturl' => (new moodle_url(
                '/local/inveniordm/lecturer/my_courses.php'
            ))->out(false),
        ]);
    }

    public function get_my_resources_context(): array
    {
        $service = new resource_service();
        $page = optional_param('page', 1, PARAM_INT);

        $data = $service->get_lecturer_my_resources($page);

        return array_merge($data, [
            'backurl' => (new moodle_url(
                '/local/inveniordm/index.php'
            ))->out(false),
        ]);
    }

    public function publish_submission(): void
    {
        $submissionid = required_param(
            'submissionid',
            PARAM_INT
        );

        $service = new submission_service();

        $service->publish_to_invenio(
            $submissionid
        );

        redirect(
            new moodle_url(
                '/local/inveniordm/lecturer/review_submission.php',
                [
                    'submissionid' => $submissionid
                ]
            ),
            'Published to Invenio successfully'
        );
    }

    public function get_review_submission_context(array $post): array
    {
        $submissionid = required_param(
            'submissionid',
            PARAM_INT
        );

        $service = new submission_service();

        if (!empty($post)) {
            $result = $service->save_review(
                $submissionid,
                trim(optional_param('grade', '', PARAM_TEXT)),
                trim(optional_param('feedback', '', PARAM_TEXT))
            );

            if ($result['success']) {
                redirect(
                    new moodle_url(
                        '/local/inveniordm/lecturer/review_submission.php',
                        [
                            'submissionid' => $submissionid
                        ]
                    ),
                    'Review saved successfully.'
                );
            }
        }

        $data = $service->get_review_submission(
            $submissionid
        );

        return array_merge($data, [
            'errors' => $result['errors'] ?? [],
            'backurl' => (new moodle_url(
                '/local/inveniordm/lecturer/view_submissions.php',
                [
                    'assignmentid' => $data['assignmentid']
                ]
            ))->out(false),
            'publishurl' => (new moodle_url(
                '/local/inveniordm/lecturer/publish_submission.php',
                [
                    'submissionid' => $submissionid
                ]
            ))->out(false),
        ]);
    }

    public function get_search_resources_to_attach_context(): array
    {
        $courseid = required_param(
            'courseid',
            PARAM_INT
        );

        $page = optional_param('page', 1, PARAM_INT);

        $search = trim(
            optional_param(
                'q',
                '',
                PARAM_TEXT
            )
        );

        $service = new resource_service();

        $data = $service->search_resources_to_attach(
            $courseid,
            $search,
            $page
        );

        return array_merge(
            $data,
            [
                'q' => $search,
                'backurl' => (
                new moodle_url(
                    '/local/inveniordm/lecturer/course_resources.php',
                    [
                        'courseid' => $courseid
                    ]
                )
                )->out(false),
                'searchurl' => (
                new moodle_url(
                    '/local/inveniordm/lecturer/search_resources_to_attach.php',
                    [
                        'courseid' => $courseid
                    ]
                )
                )->out(false)
            ]
        );
    }

    public function attach_resource(): void
    {
        global $USER;
        $courseid = required_param(
            'courseid',
            PARAM_INT
        );

        $recordid = required_param(
            'attach',
            PARAM_TEXT
        );

        $service = new resource_service();

        $service->attach_resource(
            $courseid,
            $recordid,
            $USER->id
        );

        redirect(
            new moodle_url(
                '/local/inveniordm/lecturer/search_resources_to_attach.php',
                [
                    'courseid' => $courseid
                ]
            ),
            'Attached successfully'
        );
    }

    public function process_upload($data, $user): array
    {
        $service = new upload_service();

        return $service->upload(
            $data,
            $user
        );
    }

    public function get_view_submissions_context(): array
    {
        $assignmentid = required_param(
            'assignmentid',
            PARAM_INT
        );

        $search = trim(
            optional_param(
                'search',
                '',
                PARAM_TEXT
            )
        );

        $page = optional_param('page', 1, PARAM_INT);

        $service = new submission_service();

        $data = $service->get_view_submissions(
            $assignmentid,
            $search,
            $page
        );

        return array_merge(
            $data,
            [
                'backurl' => (
                new moodle_url(
                    '/local/inveniordm/lecturer/assignments.php',
                    [
                        'courseid' => $data['courseid']
                    ]
                )
                )->out(false),

                'searchurl' => (
                new moodle_url(
                    '/local/inveniordm/lecturer/view_submissions.php',
                    [
                        'assignmentid' => $assignmentid
                    ]
                )
                )->out(false),

                'reseturl' => (
                new moodle_url(
                    '/local/inveniordm/lecturer/view_submissions.php',
                    [
                        'assignmentid' => $assignmentid
                    ]
                )
                )->out(false)
            ]
        );
    }

    public function get_create_course_context(array $postdata = []): array
    {
        global $DB;

        $service = new course_service();

        $categories = $DB->get_records(
            'course_categories',
            null,
            'sortorder ASC',
            'id, name'
        );

        $categoryoptions = [];

        foreach ($categories as $category) {
            $categoryoptions[] = [
                'id' => $category->id,
                'name' => format_string($category->name),
                'selected' => !empty($postdata['categoryid']) &&
                    (int)$postdata['categoryid'] === (int)$category->id
            ];
        }
        $visible = (int)($postdata['visible'] ?? 1);
        $format = $postdata['format'] ?? 'topics';

        $data = [
            'backurl' => (new moodle_url(
                '/local/inveniordm/index.php'
            ))->out(false),
            'categories' => $categoryoptions,
            'fullname' => $postdata['fullname'] ?? '',
            'shortname' => $postdata['shortname'] ?? '',
            'summary' => $postdata['summary'] ?? '',
            'startdate' => $postdata['startdate'] ?? '',
            'enddate' => $postdata['enddate'] ?? '',
            'numsections' => $postdata['numsections'] ?? 10,

            'istopics' => $format === 'topics',
            'isweeks' => $format === 'weeks',

            'isshown' => $visible === 1,
            'ishidden' => $visible === 0,
        ];

        if (!empty($postdata)) {
            try {
                $courseid = $service->create_course($postdata);

                $data['success'] = 'Course created successfully.';

                // reset form sau khi tạo thành công
                $data['fullname'] = '';
                $data['shortname'] = '';
                $data['summary'] = '';
                $data['startdate'] = '';
                $data['enddate'] = '';
                $data['format'] = 'topics';
                $data['numsections'] = 10;
                $data['visible'] = 1;

            } catch (Exception $e) {
                $data['error'] = $e->getMessage();
            }
        }
        return $data;
    }
}