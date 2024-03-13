<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Action\Links;
use Ada_Aba\Public\Action\Errors;
use Ada_Aba\Public\Challenge_Actions\Enroll_Action;

use function Ada_Aba\Public\Action\Links\redirect_to_error_page;

class Enroll_Workflow extends Workflow_Base
{
  private $load_handlers;
  private $learner_slug;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      Keys\ENROLL => array($this, 'handle_enroll'),
    ];
  }

  public function can_handle_load_precise()
  {
    foreach ($this->load_handlers as $key => $_) {
      if ($this->is_in_get($key)) {
        return true;
      }
    }
    return false;
  }

  public function handle_load()
  {
    foreach ($this->load_handlers as $key => $handler) {
      if ($this->is_in_get($key)) {
        call_user_func($handler);
        return;
      }
    }
  }

  public function can_handle_page()
  {
    return false;
  }

  public function handle_page()
  {
    // no actions
  }

  private function get_learner_slug()
  {
    return $_GET[Keys\ENROLL];
  }

  private function handle_enroll()
  {
    $learner_slug = $this->get_learner_slug();
    $learner = Learner::get_by_slug($learner_slug);

    $active_course = Course::get_active_course();

    if (!$learner || !$active_course) {
      Core::log(sprintf(
        '%1$s::%2$s' . ',%3$s,' . 'Learner: %4$s',
        __CLASS__,
        __FUNCTION__,
        'Invalid learner or no active course',
        $learner_slug
      ));
      redirect_to_error_page(Errors\INVALID_REQUEST);
    }

    // enqueue the action
    $action = Enroll_Action::create($learner->getEmail(), $learner_slug);
    $action->run();

    Links\redirect_to_confirm_page($action->getSlug());
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
