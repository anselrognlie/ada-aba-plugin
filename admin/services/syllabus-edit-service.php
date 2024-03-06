<?php

namespace Ada_Aba\Admin\Services;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Includes\Models\Syllabus;
use Ada_Aba\Includes\Models\Course_Lesson;

class Syllabus_Edit_Service {
  private $courseSlug;

  public function __construct($courseSlug)
  {
    $this->courseSlug = $courseSlug;
  }

  static public function get_by_member_syllabus_slug($syllabusSlug)
  {
    $syllabus = Syllabus::get_by_slug($syllabusSlug);
    $course = Course::get_by_id($syllabus->getCourseId());
    return new Syllabus_Edit_Service($course->getSlug());
  }

  public function getCourseSlug()
  {
    return $this->courseSlug;
  }

  public function getCourse()
  {
    $course = Course::get_by_slug($this->courseSlug);
    return $course;
  }

  public function getCourseLessons()
  {
    $syllabuses = Syllabus::get_by_course_slug($this->courseSlug);
    $lesson_ids = array_map(function($syllabus) {
      return $syllabus->getLessonId();
    }, $syllabuses);
    $lessons = Lesson::get_by_ids($lesson_ids);
    $lessons_idx = array_reduce($lessons, function($acc, $lesson) {
      $acc[$lesson->getId()] = $lesson;
      return $acc;
    }, []);

    $course_lessons = array_map(function($syllabus) use ($lessons_idx) {
      $lesson = $lessons_idx[$syllabus->getLessonId()];
      return new Course_Lesson(
        $lesson,
        $syllabus->getOrder(),
        $syllabus->getSlug(),
        $syllabus->isOptional()
      );
    }, $syllabuses);

    return $course_lessons;
  }

  public function getAvailableLessons()
  {
    $lessons = Lesson::all();
    $course_lessons = $this->getCourseLessons();
    $course_lesson_ids = array_fill_keys(array_map(function($course_lesson) {
      return $course_lesson->getLesson()->getId();
    }, $course_lessons), true);
    $filtered_lessons = array_filter($lessons, function($lesson) use ($course_lesson_ids) {
      return !isset($course_lesson_ids[$lesson->getId()]);
    });
    return $filtered_lessons;
  }

  public function move_up($syllabus_slug)
  {
    $course_lessons = $this->getCourseLessons();

    // locate the syllabus immediately above the specified syllabus
    $idx = -1;
    foreach ($course_lessons as $course_lesson) {
      if ($course_lesson->getSlug() === $syllabus_slug) {
        break;
      }
      $idx += 1;
    }

    if ($idx === -1) {
      // already at top
      return;
    }

    $higher_slug = $course_lessons[$idx]->getSlug();
    Syllabus::swap_order($syllabus_slug, $higher_slug);

    return Syllabus::get_by_slug($syllabus_slug);
  }

  public function move_down($syllabus_slug)
  {
    $course_lessons = $this->getCourseLessons();

    // locate the syllabus immediately above the specified syllabus
    $idx = 1;
    foreach ($course_lessons as $course_lesson) {
      if ($course_lesson->getSlug() === $syllabus_slug) {
        break;
      }
      $idx += 1;
    }

    if ($idx >= count($course_lessons)) {
      // already at bottom
      return;
    }

    $lower_slug = $course_lessons[$idx]->getSlug();
    Syllabus::swap_order($syllabus_slug, $lower_slug);

    return Syllabus::get_by_slug($syllabus_slug);
  }
}
