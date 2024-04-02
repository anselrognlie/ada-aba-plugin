<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Action\Keys;
use Ada_Aba\Public\Action\Links;

class Confirmation_Workflow extends One_Shot_Email_Workflow
{
  private $email;
  private $subject;
  private $body;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name, Keys\CONFIRMATION);
  }

  protected function handle_request_load_internal()
  {
    if (!$this->is_user_confirmed()) {
      return;
    }

    $learner_slug = $this->get_confirmed_user();
    $learner = Learner::get_by_slug($learner_slug);
    if (!$learner) {
      return;
    }

    return $this->prepare_welcome_email($learner);
  }

  protected function handle_request_page_internal()
  {
    if ($this->is_user_confirmed()) {
      return $this->handle_learner_confirmation_success();
    } else {
      return $this->handle_learner_confirmation_failed();
    }
  }

  protected function get_base_url()
  {
    $learner_slug = $this->is_user_confirmed() ? $this->get_confirmed_user() : '';
    return Links\get_confirmation_link($learner_slug);
  }

  protected function get_email()
  {
    return $this->email;
  }

  protected function get_subject()
  {
    return $this->subject;
  }

  protected function get_body()
  {
    return $this->body;
  }

  private function is_user_confirmed()
  {
    return isset($_GET[Keys\USER]);
  }

  private function get_confirmed_user()
  {
    return $_GET[Keys\USER];
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

  private function prepare_welcome_email($learner)
  {
    $first_name = $learner->getFirstName();
    $last_name = $learner->getLastName();
    $this->email = $learner->getEmail();

    $progress_link = Links\get_progress_link($learner->getSlug());

    $this->subject = 'Ada Build Confirmed';
    $this->body = self::get_registered_email_content(
      $first_name,
      $last_name,
      $this->email,
      $progress_link,
    );
  }

  //
  // output wrappers
  //

  private function get_registered_email_content(
    $first_name,
    $last_name,
    $email,
    $progress_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registered-email.php';
    return ob_get_clean();
  }

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
