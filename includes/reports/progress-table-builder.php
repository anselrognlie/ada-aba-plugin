<?php

namespace Ada_Aba\Includes\Reports;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Services\Lesson_Progress_Service;
use Ada_Aba\Includes\Services\Syllabus_Edit_Service;

class Progress_Table_Builder
{
  private $plugin_name;

  public function __construct($plugin_name)
  {
    $this->plugin_name = $plugin_name;
  }

  public function build($course) {
    $course_slug = $course->getSlug();

    $se_service = new Syllabus_Edit_Service($course_slug);
    $course_lessons = $se_service->get_course_lessons();

    $lp_service = new Lesson_Progress_Service();
    $learner_data = $lp_service->get_all_user_lesson_progress($course);

    $header = $this->generate_header($course_lessons);

    $lines = [$header];
    foreach ($learner_data as $learner_record) {
      $line = $this->generate_line($course_lessons, $learner_record);
      $lines[] = $line;
    }

    return $lines;
  }

  private function generate_header($course_lessons)
  {
    $header = ['Last Name', 'First Name', 'Email', 'Learner ID', 'Date Enrolled', '% Complete'];
    foreach ($course_lessons as $course_lesson) {
      $lesson = $course_lesson->getLesson();

      $name_parts = [$lesson->getName()];
      if ($course_lesson->isOptional()) {
        $name_parts[] = '(Optional)';
      }

      $header[] = join(' ', $name_parts);
    }

    return $header;
  }

  private function generate_line($course_lessons, $learner_record)
  {
    $line = array(
      $learner_record['last_name'],
      $learner_record['first_name'],
      $learner_record['email'],
      $learner_record['slug'],
      $learner_record['date_enrolled'],
    );

    $score_idx = count($line);

    $line[] = 0;  // placeholder that will be filled with the the calculated result

    $completed_lessons = $learner_record['completed_lessons'];
    $total_lessons_count = 0;
    $completed_lessons_count = 0;

    foreach ($course_lessons as $course_lesson) {
      $lesson = $course_lesson->getLesson();
      $lesson_slug = $lesson->getSlug();
      $date_completed = Core::safe_key($completed_lessons, $lesson_slug, '');
      $line[] = $date_completed;

      if (!$course_lesson->isOptional()) {
        $total_lessons_count += 1;
        if ($date_completed) {
          $completed_lessons_count += 1;
        }
      }
    }

    $percent_complete = $total_lessons_count > 0
      ? round($completed_lessons_count / $total_lessons_count * 100)
      : 0;

    $line[$score_idx] = $percent_complete;

    return $line;
  }
}