<?php

namespace Ada_Aba\Includes\Dto\Survey_Question;

use Ada_Aba\Includes\Dto\Question\Question_List_Item;

class Survey_Question_List_Item
{
  private $survey_question_relation;

  public function __construct($survey_question_relation)
  {
    $this->survey_question_relation = $survey_question_relation;
  }

  public function getId()
  {
    return $this->survey_question_relation->getId();
  }

  public function getSlug()
  {
    return $this->survey_question_relation->getSlug();
  }

  public function getDisplay()
  {
    $question_list_item = new Question_List_Item($this->survey_question_relation->getQuestion());
    return $question_list_item->getDisplay();
  }

  public function isOptional()
  {
    return $this->survey_question_relation->isOptional();
  }
}
