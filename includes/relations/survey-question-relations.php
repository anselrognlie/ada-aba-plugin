<?php

namespace Ada_Aba\Includes\Relations;

use Ada_Aba\Includes\Models\Survey_Question;

class Survey_Question_Relations extends Survey_Question
{
  private $survey;
  private $question;

  public function __construct($survey_question, $survey, $question)
  {
    parent::__construct(
      $survey_question->getId(),
      $survey_question->getCreatedAt(),
      $survey_question->getUpdatedAt(),
      $survey_question->getDeletedAt(),
      $survey_question->getSurveyId(),
      $survey_question->getQuestionId(),
      $survey_question->getOrder(),
      $survey_question->getSlug(),
      $survey_question->isOptional(),
    );

    $this->survey = $survey;
    $this->question = $question;
  }

  public function getSurvey()
  {
    return $this->survey;
  }

  public function getQuestion()
  {
    return $this->question;
  }
}
