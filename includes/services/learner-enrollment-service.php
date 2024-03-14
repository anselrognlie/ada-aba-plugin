<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Completed_Lesson;
use Ada_Aba\Includes\Models\Enrollment;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Lesson;

class Learner_Enrollment_Service
{
  public function get_learner_by_enrollment_slug($enrollment_slug)
  {
    $enrollment = Enrollment::get_by_slug($enrollment_slug);
    if (!$enrollment) {
      throw new Aba_Exception('Could not get enrollment');
    }

    $learner = Learner::get_by_id($enrollment->getLearnerId());
    if (!$learner) {
      throw new Aba_Exception('Could not get learner');
    }

    return $learner;
  }
}
