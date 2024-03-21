<?php

namespace Ada_Aba\Admin\Fragments\Survey_Questions;

function get_available_questions_fragment(
  $question,  // a question list item
) {
  ob_start();
  include __DIR__ . '/../partials/survey-question-available-question-fragment.php';
  return ob_get_clean();
}

function get_survey_questions_fragment(
  $survey_question_relation,  // a survey question list item
) {
  ob_start();
  include __DIR__ . '/../partials/survey-question-question-fragment.php';
  return ob_get_clean();
}
