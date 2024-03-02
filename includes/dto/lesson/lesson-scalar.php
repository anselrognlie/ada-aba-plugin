<?php

namespace Ada_Aba\Includes\Dto\Lesson;

class Lesson_Scalar implements \JsonSerializable
{
  private $lesson;

  public function __construct($lesson)
  {
    $this->lesson = $lesson;
  }

  public function jsonSerialize()
  {
    return array(
      'id' => $this->lesson->getId(),
      'name' => $this->lesson->getName(),
      'slug' => $this->lesson->getSlug(),
    );
  }
}
