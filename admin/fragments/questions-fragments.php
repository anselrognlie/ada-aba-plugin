<?php

namespace Ada_Aba\Admin\Fragments\Questions;

function get_questions_fragment(
  $question_list_item,
) {
  $question = $question_list_item;  // needed for template

  ob_start();
  include __DIR__ . '/../partials/questions-question-fragment.php';
  return ob_get_clean();
}

