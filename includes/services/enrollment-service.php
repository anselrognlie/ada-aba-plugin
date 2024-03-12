<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Includes\Models\Syllabus;
use Ada_Aba\Includes\Models\Course_Lesson;
use Ada_Aba\Includes\Models\Enrollment;
use Ada_Aba\Includes\Models\Learner;

class Enrollment_Service {
  private $learnerSlug;

  public function __construct($learnerSlug)
  {
    $this->learnerSlug = $learnerSlug;
  }

  public function getLearnerSlug()
  {
    return $this->learnerSlug;
  }

  public function getLearner()
  {
    $learner = Learner::get_by_slug($this->learnerSlug);
    return $learner;
  }

  public function enroll_by_slug($course_slug)
  {
    $learner = $this->getLearner();
    $course = Course::get_by_slug($course_slug);
    $enrollment = Enrollment::create($learner->getId(), $course->getId());
    $enrollment->insert();
    return $enrollment;
  }

  public function enroll_in_default()
  {
    $learner = $this->getLearner();
    $course = Course::get_active_course();
    if (!$course) {
      return null;  // no active course
    }

    $enrollment = Enrollment::create($learner->getId(), $course->getId());
    $enrollment->insert();
    return $enrollment;
  }
}
