<?php

namespace Ada_Aba\Admin\Fragments\Courses;

function get_courses_fragment(
  $course,
) {
  ob_start();
  include __DIR__ . '/../partials/courses-course-fragment.php';
  return ob_get_clean();
}

