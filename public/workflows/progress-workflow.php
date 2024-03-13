<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Dto\Learner_Course\Learner_Course_Progress;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Services\Enrollment_Service;
use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Action\Links;

class Progress_Workflow extends Workflow_Base
{
  private $learner_slug;
  private $learner;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
  }

  public function can_handle_load_precise()
  {
    return false;
  }

  public function handle_load()
  {
    // no actions
  }

  public function can_handle_page()
  {
    return $this->is_in_get(Keys\USER);
  }

  public function handle_page()
  {
    $this->learner_slug = $this->get_learner_slug();
    $this->learner = Learner::get_by_slug($this->learner_slug);
    if (!$this->learner) {
      return $this->handle_learner_not_found();
    }

    return $this->handle_progress();
  }

  private function get_learner_slug()
  {
    return $_GET[Keys\USER];
  }

  private function handle_progress()
  {
    $enrollment_service = new Enrollment_Service($this->learner_slug);
    $learner_courses = array_map(function ($learner_course) {
      return new Learner_Course_Progress($learner_course);
    }, $enrollment_service->get_learner_courses());

    $active_course = Course::get_active_course();

    $enroll_link = Links\get_enroll_link($this->learner_slug);

    return $this->get_progress_content($learner_courses, $active_course, $enroll_link);
  }

  private function handle_learner_not_found()
  {
    return $this->get_progress_error_content([
      'Unable to retrieve progress for the specified learner.
      Please double check your progress link and try again.'
    ]);
  }

  //
  // output wrappers
  //

  private function get_progress_content($learner_courses, $active_course, $enroll_link)
  {
    ob_start();
    include __DIR__ . '/../partials/progress-page.php';
    return ob_get_clean();
  }

  private function get_progress_error_content($errors)
  {
    ob_start();
    include __DIR__ . '/../partials/progress-error.php';
    return ob_get_clean();
  }
}