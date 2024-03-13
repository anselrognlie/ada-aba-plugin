<?php


namespace Ada_Aba\Includes\Dto\Learner_Course;

class Learner_Lesson_Progress
{
  private $course_lesson;
  private $is_complete;

  public function __construct($course_lesson, $is_complete)
  {
    $this->course_lesson = $course_lesson;
    $this->is_complete = $is_complete;
  }

  public function getName()
  {
    return $this->course_lesson->getLesson()->getName();
  }

  public function isOptional()
  {
    return $this->course_lesson->isOptional();
  }

  public function isComplete()
  {
    return $this->is_complete;
  }
}
