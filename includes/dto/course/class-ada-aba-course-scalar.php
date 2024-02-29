<?php

namespace Dto\Course;

class Ada_Aba_Course_Scalar implements \JsonSerializable {

  private $course;

  public function __construct($course) {
    $this->course = $course;
  }

  public function jsonSerialize() {
    return array(
      'id' => $this->course->getId(),
      'name' => $this->course->getName(),
      'slug' => $this->course->getSlug(),
      'active' => $this->course->isActive(),
    );
  }
}