<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Models\Enrollment;
use Ada_Aba\Includes\Services\Learner_Enrollment_Service;
use Ada_Aba\Public\Action\Emails;
use Ada_Aba\Includes\Action\Keys;

use Ada_Aba\Public\Action\Links;

abstract class One_Shot_Email_Workflow extends Workflow_Base
{
  private $load_handlers;
  private $page_handlers;

  protected abstract function handle_request_load_internal();
  protected abstract function handle_request_page_internal();
  protected abstract function get_base_url();
  protected abstract function get_email();
  protected abstract function get_subject();
  protected abstract function get_body();

  public function __construct($plugin_name, $key)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      $key => array($this, 'handle_request_load'),
    ];
    $this->page_handlers = [
      $key => array($this, 'handle_request_page'),
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
    foreach ($this->page_handlers as $key => $_) {
      if ($this->is_in_get($key)) {
        return true;
      }
    }
    return false;
  }

  public function handle_page()
  {
    foreach ($this->page_handlers as $key => $handler) {
      if ($this->is_in_get($key)) {
        return call_user_func($handler);
      }
    }
  }

  private function mark_sent($url)
  {
    return "$url&" . Keys\SENT;
  }

  private function handle_request_load()
  {
    if ($this->is_in_get(Keys\SENT)) {
      // this is our redirected page, so don't send again
      return;
    }

    $this->handle_request_load_internal();
    $to = $this->get_email();
    $subject = $this->get_subject();
    $body = $this->get_body();

    Emails::mail($to, $subject, $body);

    Links\redirect_to_page($this->mark_sent($this->get_base_url()));
  }

  private function handle_request_page()
  {
    $body = $this->handle_request_page_internal();
    $resend_link = $this->get_base_url();
    $footer = $this->get_page_footer($resend_link);

    return $body . $footer;
  }

  private function get_page_footer($resend_link)
  {
    ob_start();
    include __DIR__ . '/../partials/one-shot-footer-page.php';
    return ob_get_clean();
  }
}
