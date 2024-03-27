<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Models\Survey_Question;

class Survey_Service
{
  public function get_by_survey_question_slug($survey_question_slug)
  {
    $survey_question = Survey_Question::get_by_slug($survey_question_slug);
    return Survey::get_by_id($survey_question->getSurveyId());
  }
}
