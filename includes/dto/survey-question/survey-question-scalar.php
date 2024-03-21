<?php

namespace Ada_Aba\Includes\Dto\Survey_Question;

class Survey_Question_Scalar implements \JsonSerializable
{
  private $survey_question;

  public function __construct($survey_question)
  {
    $this->survey_question = $survey_question;
  }

  public function jsonSerialize()
  {
    return array(
      'id' => $this->survey_question->getId(),
      'survey_id' => $this->survey_question->getSurveyId(),
      'question_id' => $this->survey_question->getQuestionId(),
      'order' => $this->survey_question->getOrder(),
      'slug' => $this->survey_question->getSlug(),
      'optional' => $this->survey_question->isOptional(),
    );
  }
}
