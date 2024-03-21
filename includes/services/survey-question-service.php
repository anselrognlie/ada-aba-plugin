<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Includes\Models\Survey_Question;

class Survey_Question_Service
{
  public function create_by_slugs(
    $survey_slug,
    $question_slug,
    $order = -1,
    $optional = false,
  ) {
    $survey = Survey::get_by_slug($survey_slug);
    $question = Question::get_by_slug($question_slug);

    if (!$survey) {
      throw new Aba_Exception('Survey not found');
    }

    if (!$question) {
      throw new Aba_Exception('Question not found');
    }

    return Survey_Question::create(
      $survey->getId(),
      $question->getId(),
      $order,
      $optional,
    );
  }

  public static function get_by_survey_slug($survey_slug)
  {
    $survey = Survey::get_by_slug($survey_slug);

    if (!$survey) {
      return [];
    }

    return Survey_Question::get_by_survey_id($survey->getId());
  }
}
