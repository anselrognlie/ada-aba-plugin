<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Enrollment;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Learner_Course;

class Enrollment_Service
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

  public function enroll_by_slug($course_slug)
  {
    $course = Course::get_by_slug($course_slug);
    $enrollment = Enrollment::create($this->learner->getId(), $course->getId());
    $enrollment->insert();
    return $enrollment;
  }

  public function enroll_in_default()
  {
    $course = Course::get_active_course();
    if (!$course) {
      return null;  // no active course
    }

    $enrollment = Enrollment::create($this->learner->getId(), $course->getId());
    $enrollment->insert();
    return $enrollment;
  }

  public function get_learner_courses()
  {
    $enrollments = Enrollment::get_by_learner_id(
      $this->learner->getId(),
      by_priority: true
    );
    $course_ids = array_map(function ($enrollment) {
      return $enrollment->getCourseId();
    }, $enrollments);

    $courses = Course::get_by_ids($course_ids);
    $courses_idx = array_reduce($courses, function ($acc, $course) {
      $acc[$course->getId()] = $course;
      return $acc;
    }, []);

    $course_lessons = array_map(function ($enrollment) use ($courses_idx) {
      $course = $courses_idx[$enrollment->getCourseId()];
      return new Learner_Course(
        $course,
        $enrollment->getSlug(),
        $enrollment->getStartedAt(),
        $enrollment->getCompletedAt(),
        $enrollment->getCompletion()
      );
    }, $enrollments);

    return $course_lessons;
  }
}
