<?php


namespace Ada_Aba\Includes\Dto\Learner_Course;

class Learner_Course_Progress
{
  private $learner_course;
  private $learner_lessons_progress;

  public function __construct($learner_course, $learner_lessons_progress)
  {
    $this->learner_course = $learner_course;
    $this->learner_lessons_progress = $learner_lessons_progress;
  }

  public function getCourseName()
  {
    return $this->learner_course->getCourse()->getName();
  }

  public function isComplete()
  {
    return $this->learner_course->isComplete();
  }

  public function getLessons()
  {
    return $this->learner_lessons_progress;
  }
}
