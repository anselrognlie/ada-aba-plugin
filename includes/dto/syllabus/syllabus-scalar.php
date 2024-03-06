<?php

namespace Ada_Aba\Includes\Dto\Syllabus;

class Syllabus_Scalar implements \JsonSerializable
{
  private $syllabus;

  public function __construct($syllabus)
  {
    $this->syllabus = $syllabus;
  }

  public function jsonSerialize()
  {
    return array(
      'id' => $this->syllabus->getId(),
      'course_id' => $this->syllabus->getCourseId(),
      'lesson_id' => $this->syllabus->getLessonId(),
      'order' => $this->syllabus->getOrder(),
      'slug' => $this->syllabus->getSlug(),
      'optional' => $this->syllabus->isOptional(),
    );
  }
}
