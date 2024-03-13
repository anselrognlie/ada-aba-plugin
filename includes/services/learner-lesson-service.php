<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Completed_Lesson;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Lesson;

class Learner_Lesson_Service
{
  private $learner_slug;
  private $learner;

  public function __construct($learner_slug)
  {
    $this->learner_slug = $learner_slug;
    $this->learner = Learner::get_by_slug($learner_slug);
  }

  public function getLearnerSlug()
  {
    return $this->learner_slug;
  }

  public function getLearner()
  {
    return $this->learner;
  }

  public function complete_lesson($lesson_slug)
  {
    $learner = $this->learner;
    $lesson = Lesson::get_by_slug($lesson_slug);

    if (!$learner || !$lesson) {
      throw new Aba_Exception('Could not get learner or lesson');
    }

    $completed_lesson = Completed_Lesson::create(
      $learner->getId(),
      $lesson->getId(),
    );
    $completed_lesson->insert();
    return $completed_lesson;
  }
}
