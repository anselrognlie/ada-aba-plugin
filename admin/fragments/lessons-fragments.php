<?php

namespace Ada_Aba\Admin\Fragments\Lessons;

function get_lessons_fragment(
  $lesson,
) {
  ob_start();
  include __DIR__ . '/../partials/lessons-lesson-fragment.php';
  return ob_get_clean();
}

