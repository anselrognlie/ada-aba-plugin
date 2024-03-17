<?php


namespace Ada_Aba\Includes\Dto\Learner_Course;

class Learner_Lesson_Progress
{
  private $course_lesson;
  private $complete_link;
  private $is_complete;

  public function __construct($course_lesson, $complete_link, $is_complete)
  {
    $this->course_lesson = $course_lesson;
    $this->complete_link = $complete_link;
    $this->is_complete = $is_complete;
  }

  public function getName()
  {
    return $this->course_lesson->getLesson()->getName();
  }

  public function getUrl()
  {
    return $this->course_lesson->getLesson()->getUrl();
  }

  public function getSlug()
  {
    return $this->course_lesson->getLesson()->getSlug();
  }

  public function getCompleteLink()
  {
    return $this->complete_link;
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
