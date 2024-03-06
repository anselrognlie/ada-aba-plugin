<?php

namespace Ada_Aba\Includes\Models;

class Course_Lesson {
  private $lesson;
  private $order;
  private $slug;
  private $optional;

  public function __construct(
    $lesson,
    $order,
    $slug,
    $optional
  ) {
    $this->lesson = $lesson;
    $this->order = $order;
    $this->slug = $slug;
    $this->optional = $optional;
  }

  public function getLesson() {
    return $this->lesson;
  }

  public function setLesson($lesson) {
    $this->lesson = $lesson;
  }

  public function getOrder() {
    return $this->order;
  }

  public function setOrder($order) {
    $this->order = $order;
  }

  public function getSlug() {
    return $this->slug;
  }

  public function setSlug($slug) {
    $this->slug = $slug;
  }

  public function isOptional() {
    return $this->optional;
  }

  public function setOptional($optional) {
    $this->optional = $optional;
  }

  static public function get_by_slug($slug) {
    $syllabus = Syllabus::get_by_slug($slug);
    $lesson = Lesson::get_by_id($syllabus->getLessonId());
    return new Course_Lesson(
      $lesson,
      $syllabus->getOrder(),
      $syllabus->getSlug(),
      $syllabus->isOptional()
    );
  }
}