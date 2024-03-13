<?php


namespace Ada_Aba\Includes\Dto\Learner_Course;

class Learner_Course_Progress
{
  private $learner_course;

  public function __construct($learner_course)
  {
    $this->learner_course = $learner_course;
  }

  public function getCourseName()
  {
    return $this->learner_course->getCourse()->getName();
  }

  public function isComplete()
  {
    return $this->learner_course->isComplete();
  }
}
