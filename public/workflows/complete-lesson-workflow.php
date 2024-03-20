<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Action\Links;
use Ada_Aba\Public\Action\Errors;
use Ada_Aba\Public\Challenge_Actions\Complete_Lesson_Action;

use function Ada_Aba\Public\Action\Links\redirect_to_error_page;

class Complete_Lesson_Workflow extends Workflow_Base
{
  private $load_handlers;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      Keys\COMPLETE => array($this, 'handle_complete'),
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

  private function get_lesson_slug()
  {
    return $_GET[Keys\COMPLETE];
  }

  private function get_learner_slug()
  {
    return Core::safe_key($_GET, Keys\USER, '');
  }

  private function handle_complete()
  {
    $learner_slug = $this->get_learner_slug();
    $learner = Learner::get_by_slug($learner_slug);

    $lesson_slug = $this->get_lesson_slug();
    $lesson = Lesson::get_by_slug($lesson_slug);

    if (!$learner || !$lesson || !$lesson->canCompleteOnProgress()) {
      Core::log(sprintf(
        implode(',', ['%1$s::%2$s','%3$s','Learner: %4$s','Lesson: %5$s']),
        __CLASS__,
        __FUNCTION__,
        'Invalid learner or lesson',
        $learner_slug,
        $lesson_slug,
      ));
      redirect_to_error_page(Errors\INVALID_REQUEST);
    }

    // enqueue the action
    $action = Complete_Lesson_Action::create($learner->getEmail(), $lesson_slug, $learner_slug);
    $action->run();

    Links\redirect_to_confirm_page($action->getSlug());
  }
}
