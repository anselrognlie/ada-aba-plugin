<?php

namespace Ada_Aba\Admin\Fragments\Courses;

function get_courses_fragment(
  $course,
) {
  ob_start();
  include __DIR__ . '/../partials/ada-aba-admin-courses-course-fragment.php';
  return ob_get_clean();
}

