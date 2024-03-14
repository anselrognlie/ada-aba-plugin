<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Completed_Lesson;
use Ada_Aba\Includes\Models\Enrollment;
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

    $this->try_complete_courses_by_lesson($lesson);

    return $completed_lesson;
  }

  private function try_complete_courses_by_lesson($lesson)
  {
    // find any course that contain the lesson
    // get all the other lessons for each course
    // for any course whose non-optional lessons are all completed, mark the course as completed

    $courses = $this->get_incomplete_courses_by_lesson($lesson);
    
    $course_lesson_service = new Course_Lesson_Service();
    $course_lessons = array_map(function ($course) use ($course_lesson_service) {
      $required_lessons = $course_lesson_service->get_required_lessons_by_course($course);
      return $required_lessons;
    }, $courses);

    $completed_lessons = Completed_Lesson::get_by_learner_id($this->learner->getId());
    $completed_idx = array_reduce($completed_lessons, function ($acc, $completed_lesson) {
      $acc[$completed_lesson->getLessonId()] = true;
      return $acc;
    }, []);

    foreach (range(0, count($courses) - 1) as $i) {
      $course = $courses[$i];
      $required_lessons = $course_lessons[$i];

      $all_completed = array_reduce($required_lessons, function ($acc, $lesson) use ($completed_idx) {
        return $acc && isset($completed_idx[$lesson->getId()]);
      }, true);

      if ($all_completed) {
        $enrollment = Enrollment::get_by_learner_course($this->learner->getId(), $course->getId());
        if ($enrollment) {
          $enrollment->complete();
        }
      }
    }
  }

  private function get_incomplete_courses_by_lesson($lesson)
  {
    $course_lesson_service = new Course_Lesson_Service();
    $courses = $course_lesson_service->get_courses_by_lesson($lesson);

    $enrollment_service = new Enrollment_Service($this->learner_slug);
    $learner_courses = $enrollment_service->get_learner_courses();
    $enrolled_idx = array_reduce($learner_courses, function ($acc, $learner_course) {
      $acc[$learner_course->getCourse()->getId()] = $learner_course;
      return $acc;
    }, []);

    $incomplete_courses = array_filter($courses, function ($course) use ($enrolled_idx) {
      $course_id = $course->getId();
      if (!isset($enrolled_idx[$course_id])) {
        return false;
      }
      $learner_course = $enrolled_idx[$course_id];
      return !$learner_course->isComplete();
    });

    return $incomplete_courses;
  }
}
