<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Action\Links;

class Confirmation_Workflow extends Workflow_Base
{
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
    return $this->is_in_get(Keys\CONFIRMATION);
  }

  public function handle_page()
  {
    if ($this->is_user_confirmed()) {
      return $this->handle_learner_confirmation_success();
    } else {
      return $this->handle_learner_confirmation_failed();
    }
  }

  private function is_user_confirmed()
  {
    return isset($_GET['u']);
  }

  private function get_confirmed_user()
  {
    return $_GET['u'];
  }

  private function handle_learner_confirmation_success()
  {
    $user = $this->get_confirmed_user();
    $progress_link = Links\get_progress_link($user) ?? '';
    return $this->get_registered_content($progress_link);
  }

  private function handle_learner_confirmation_failed()
  {
    return $this->get_registered_error_content();
  }

  //
  // output wrappers
  //

  private function get_registered_content(
    $progress_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registered-page.php';
    return ob_get_clean();
  }

  private function get_registered_error_content()
  {
    ob_start();
    include __DIR__ . '/../partials/registered-error.php';
    return ob_get_clean();
  }
}
