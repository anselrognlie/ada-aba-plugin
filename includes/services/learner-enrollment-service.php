<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Dto\Course\Certificate_Details;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Enrollment;
use Ada_Aba\Includes\Models\Learner;

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

  public function get_certificate_details_by_completion_slug($completion_slug)
  {
    $enrollment = Enrollment::get_by_completion_slug($completion_slug);
    if (!$enrollment) {
      throw new Aba_Exception('Could not get enrollment');
    }

    $learner = Learner::get_by_id($enrollment->getLearnerId());
    if (!$learner) {
      throw new Aba_Exception('Could not get learner');
    }

    $course = Course::get_by_id($enrollment->getCourseId());
    if (!$course) {
      throw new Aba_Exception('Could not get course');
    }

    return new Certificate_Details(
      $course->getName(),
      $learner->getFirstName() . ' ' . $learner->getLastName(),
      new \DateTime($enrollment->getCompletedAt()),
    );
  }
}
