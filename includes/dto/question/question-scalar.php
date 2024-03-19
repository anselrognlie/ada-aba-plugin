<?php

namespace Ada_Aba\Includes\Dto\Question;

class Question_Scalar implements \JsonSerializable
{
  private $question;

  public function __construct($question)
  {
    $this->question = $question;
  }

  public function jsonSerialize()
  {
    return array(
      'id' => $this->question->getId(),
      'slug' => $this->question->getSlug(),
      'builder' => $this->question->getBuilder(),
      'prompt' => $this->question->getPrompt(),
      'description' => $this->question->getDescription(),
      'data' => $this->question->getData(),
    );
  }
}
