<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Includes\Models\Syllabus;

class Course_Lesson_Service
{
  public function get_courses_by_lesson_slug($lesson_slug)
  {
    $lesson = Lesson::get_by_slug($lesson_slug);
    if (!$lesson) {
      return [];
    }

    return $this->get_courses_by_lesson($lesson);
  }

  public function get_courses_by_lesson($lesson)
  {
    $syllabuses = Syllabus::get_by_lesson_id($lesson->getId());
    $course_ids = array_keys(array_reduce($syllabuses, function ($acc, $syllabus) {
      $acc[$syllabus->getCourseId()] = true;
      return $acc;
    }, []));
    $courses = Course::get_by_ids($course_ids);
    return $courses;
  }

  public function get_required_lessons_by_course_slug($course_slug)
  {
    $course = Course::get_by_slug($course_slug);
    if (!$course) {
      return [];
    }

    return $this->get_required_lessons_by_course($course);
  }

  public function get_required_lessons_by_course($course)
  {
    $syllabuses = Syllabus::get_by_course_id($course->getId());
    $lesson_ids = array_keys(array_reduce($syllabuses, function ($acc, $syllabus) {
      if (!$syllabus->isOptional()) {
        $acc[$syllabus->getLessonId()] = true;
      }
      return $acc;
    }, []));
    $lessons = Lesson::get_by_ids($lesson_ids);
    return $lessons;
  }
}