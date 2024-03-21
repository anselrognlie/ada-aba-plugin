<?php

namespace Ada_Aba\Admin\Fragments\Surveys;

function get_surveys_fragment(
  $survey,
) {
  ob_start();
  include __DIR__ . '/../partials/surveys-survey-fragment.php';
  return ob_get_clean();
}
