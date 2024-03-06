<?php

namespace Ada_Aba\Admin\Fragments\Course_Lessons;

function get_available_lessons_fragment(
  $lesson,
) {
  ob_start();
  include __DIR__ . '/../partials/syllabus-available-lesson-fragment.php';
  return ob_get_clean();
}

function get_course_lessons_fragment(
  $course_lesson,
) {
  ob_start();
  include __DIR__ . '/../partials/syllabus-course-lesson-fragment.php';
  return ob_get_clean();
}
