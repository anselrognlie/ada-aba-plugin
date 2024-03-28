<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Dto\Learner_Course\Learner_Course_Progress_Builder;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Models\Surveyed_Learner;
use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Action\Links;

use function Ada_Aba\Public\Action\Links\redirect_to_survey_page;

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
    // make sure we have a learner
    $learner_slug = Core::safe_key($_GET, Keys\USER, '');
    $this->learner = Learner::get_by_slug($learner_slug);
    return ((bool)$this->learner);
  }

  public function handle_load()
  {
    $this->handle_survey();
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

  private function handle_survey() {
    $survey = Survey::get_active_survey();
    $learner_slug = $this->get_learner_slug();

    if ($survey && !Surveyed_Learner::contains($learner_slug)) {
      redirect_to_survey_page($learner_slug);
    }
  }

  private function handle_progress()
  {
    $builder = new Learner_Course_Progress_Builder($this->learner_slug);
    $learner_courses = $builder->build();

    $active_course = Course::get_active_course();

    $enroll_link = Links\get_enroll_link($this->learner_slug);

    return $this->get_progress_content($this->learner_slug, $learner_courses, $active_course, $enroll_link);
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

  private function get_progress_content($learner_slug, $learner_courses, $active_course, $enroll_link)
  {
    $plugin_name = $this->plugin_name;
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
