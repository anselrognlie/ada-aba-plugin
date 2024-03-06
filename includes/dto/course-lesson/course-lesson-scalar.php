<?php

namespace Ada_Aba\Includes\Dto\Course_Lesson;

class Course_Lesson_Scalar implements \JsonSerializable
{
  private $course_lesson;

  public function __construct($course_lesson)
  {
    $this->course_lesson = $course_lesson;
  }

  public function jsonSerialize()
  {
    return array(
      'lesson_slug' => $this->course_lesson->getLesson()->getSlug(),
      'order' => $this->course_lesson->getOrder(),
      'slug' => $this->course_lesson->getSlug(),
      'optional' => $this->course_lesson->isOptional(),
    );
  }
}