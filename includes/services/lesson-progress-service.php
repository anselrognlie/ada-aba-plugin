<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Models\Completed_Lesson;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Enrollment;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Includes\Models\Syllabus;

class Lesson_Progress_Service
{
  public function get_all_user_lesson_progress($course)
  {
    $course_slug = $course->getSlug();

    global $wpdb;
    $course_table_name = $wpdb->prefix . Course::$table_name;
    $syllabus_table_name = $wpdb->prefix . Syllabus::$table_name;
    $lesson_table_name = $wpdb->prefix . Lesson::$table_name;
    $enrollment_table_name = $wpdb->prefix . Enrollment::$table_name;
    $completed_lesson_table_name = $wpdb->prefix . Completed_Lesson::$table_name;
    $learner_table_name = $wpdb->prefix . Learner::$table_name;

    $query = $wpdb->prepare(
      "SELECT lr.*, e.started_at as date_enrolled, l.slug as lesson_slug, cl.completed_at as date_completed"
        . " FROM $course_table_name c"
        . " JOIN $syllabus_table_name s ON s.course_id = c.id"
        . " JOIN $lesson_table_name l ON s.lesson_id = l.id"
        . " JOIN $enrollment_table_name e ON e.course_id = c.id"
        . " JOIN $completed_lesson_table_name cl ON e.learner_id = cl.learner_id AND cl.lesson_id = s.lesson_id"
        . " JOIN $learner_table_name lr ON e.learner_id = lr.id"
        . " WHERE c.slug = %s"
        . " ORDER BY lr.last_name;",
      $course_slug
    );

    $result = $wpdb->get_results($query, 'OBJECT');

    if (!$result) {
      return [];
    }

    // php associative arrays are ordered by insertion, so this will maintain
    // the order of the results returned by the query
    $learner_data = [];
    foreach ($result as $row) {
      $learner_slug = $row->slug;
      if (!array_key_exists($learner_slug, $learner_data)) {
        $learner_data[$learner_slug] = array(
          'first_name' => $row->first_name,
          'last_name' => $row->last_name,
          'email' => $row->email,
          'slug' => $row->slug,
          'date_enrolled' => $row->date_enrolled,
          'completed_lessons' => [],
        );
      }
      $lessons = &$learner_data[$learner_slug]['completed_lessons'];
      $lesson_slug = $row->lesson_slug;
      $lessons[$lesson_slug] = $row->date_completed;
    }

    // structure looks like
    // [
    //   learner_slug => [
    //     'first_name' => 'first_name',
    //     'last_name' => 'last_name',
    //     'email' => 'email',
    //     'date_enrolled' => 'date_enrolled',
    //     'completed_lessons' => [
    //       lesson_slug => date_completed,
    //       ...
    //     ],
    //   ],
    //   ...
    // ]

    return $learner_data;
  }
}
