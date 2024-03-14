<?php

namespace Ada_Aba\Includes\Dto\Learner_Course;

use Ada_Aba\Includes\Models\Completed_Lesson;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Services\Enrollment_Service;
use Ada_Aba\Includes\Services\Syllabus_Edit_Service;

use function Ada_Aba\Public\Action\Links\get_complete_lesson_link;
use function Ada_Aba\Public\Action\Links\get_request_certificate_link;

class Learner_Course_Progress_Builder
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

  public function build()
  {
    $enrollment_service = new Enrollment_Service($this->learner_slug);
    $learner_courses = $enrollment_service->get_learner_courses();

    $learner_course_lessons = array_map(function ($learner_course) {
      $syllabus_service = new Syllabus_Edit_Service($learner_course->getCourse()->getSlug());
      return $syllabus_service->get_course_lessons();
    }, $learner_courses);

    $learner_completed_lessons = Completed_Lesson::get_by_learner_id($this->learner->getId());
    $completed_idx = array_reduce($learner_completed_lessons, function ($acc, $completed_lesson) {
      $acc[$completed_lesson->getLessonId()] = $completed_lesson;
      return $acc;
    }, []);

    $result = array_map(
      function ($learner_courses_model, $course_lessons)
      use ($completed_idx) {
        $learner_lessons_progress = array_map(
          function ($course_lesson) use ($completed_idx) {
            $is_complete = isset($completed_idx[$course_lesson->getLesson()->getId()]);
            $complete_link = get_complete_lesson_link($course_lesson->getLesson()->getSlug(), $this->learner_slug);
            return new Learner_Lesson_Progress($course_lesson, $complete_link, $is_complete);
          },
          $course_lessons
        );

        $request_certificate_link = get_request_certificate_link($learner_courses_model->getSlug());

        return new Learner_Course_Progress(
          $learner_courses_model,
          $request_certificate_link,
          $learner_lessons_progress
        );
      },
      $learner_courses,
      $learner_course_lessons
    );

    return $result;
  }
}
