<?php

namespace Ada_Aba\Includes\Dto\Question;

class Question_List_Item
{
  private $question;

  const MAX_DISPLAY_LENGTH = 50;

  public function __construct($question)
  {
    $this->question = $question;
  }

  public function getId()
  {
    return $this->question->getId();
  }

  public function getSlug()
  {
    return $this->question->getSlug();
  }

  public function getDisplay()
  {
    $prompt = $this->question->getPrompt();
    $description = $this->question->getDescription();
    $combined = trim($prompt . ' ' . $description);
    if (strlen($combined) > self::MAX_DISPLAY_LENGTH) {
      $combined = substr($combined, 0, self::MAX_DISPLAY_LENGTH) . '...';
    }

    return $combined;
  }
}
