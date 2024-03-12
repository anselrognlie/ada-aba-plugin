<?php

namespace Ada_Aba\Includes\Models;

class Learner_Course {
  private $course;
  private $slug;
  private $started_at;
  private $completed_at;
  private $completion;

  public function __construct(
    $course,
    $slug,
    $started_at,
    $completed_at,
    $completion,
  ) {
    $this->course = $course;
    $this->slug = $slug;
    $this->started_at = $started_at;
    $this->completed_at = $completed_at;
    $this->completion = $completion;
  }

  public function getCourse() {
    return $this->course;
  }

  public function setCourse($course) {
    $this->course = $course;
  }

  public function getSlug() {
    return $this->slug;
  }

  public function setSlug($slug) {
    $this->slug = $slug;
  }

  public function getStartedAt() {
    return $this->started_at;
  }

  public function setStartedAt($started_at) {
    $this->started_at = $started_at;
  }

  public function getCompletedAt() {
    return $this->completed_at;
  }

  public function setCompletedAt($completed_at) {
    $this->completed_at = $completed_at;
  }

  public function getCompletion() {
    return $this->completion;
  }

  public function isCompleted() {
    return $this->completion;
  }

  public function setCompletion($completion) {
    $this->completion = $completion;
  }

  static public function get_by_slug($slug) {
    $enrollment = Enrollment::get_by_slug($slug);
    $course = Course::get_by_id($enrollment->getCourseId());

    return new Learner_Course(
      $course,
      $slug,
      $enrollment->getStartedAt(),
      $enrollment->getCompletedAt(),
      $enrollment->getCompletion(),
    );
  }
}