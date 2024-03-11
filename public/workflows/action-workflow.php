<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Action\Links;
use Ada_Aba\Public\Challenge_Actions\Action_Base;

class Action_Workflow extends Workflow_Base
{
  private $load_handlers;
  private $page_handlers;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      Keys\VERIFY => array($this, 'handle_verify'),
      Keys\RESEND => array($this, 'handle_resend'),
    ];
    $this->page_handlers = [
      Keys\CONFIRM => array($this, 'handle_confirm'),
      Keys\VERIFY => array($this, 'handle_unknown'),
      Keys\RESEND => array($this, 'handle_unknown'),
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

  private function handle_confirm()
  {
    $slug = $_GET[Keys\CONFIRM];
    $action = Action_Base::get_by_slug($slug);

    if (!$action) {
      // unable to verify due to invalid or expired slug
      Core::log(sprintf('%1$s: invalid or expired slug: %2$s', __FUNCTION__, $slug));

      return $this->handle_unknown();
    }

    [$_, $body] = $action->get_email_content();
    $resend_link = Links\get_resend_link($slug);
    $footer = $this->get_page_footer($resend_link);

    return $body . $footer;
  }

  private function handle_verify()
  {
    $nonce = $_GET[Keys\VERIFY];
    Core::log(sprintf('%1$s: nonce: %2$s', __FUNCTION__, $nonce));
    $action = Action_Base::get_by_nonce($nonce);

    if (!$action) {
      // unable to verify due to invalid or expired nonce
      Core::log(sprintf('%1$s: invalid or expired nonce: %2$s', __FUNCTION__, $nonce));

      // allow to fall-through to error page
      return;
    }

    $action->complete();
    exit;
  }

  private function handle_resend()
  {
    $slug = $_GET[Keys\RESEND];
    $action = Action_Base::get_by_slug($slug);

    if (!$action) {
      // unable to verify due to invalid or expired nonce
      Core::log(sprintf('%1$s: invalid or expired slug: %2$s', __FUNCTION__, $slug));

      // allow to fall-through to error page
      return;
    }

    $action->notify();

    Links\redirect_to_confirm_page($slug);
  }

  private function handle_unknown()
  {
    // unable to verify or resend due to invalid or expired nonce
    return $this->get_action_error();
  }

  private function get_page_footer($resend_link)
  {
    ob_start();
    include __DIR__ . '/../partials/action-footer-page.php';
    return ob_get_clean();
  }

  private function get_action_error()
  {
    ob_start();
    include __DIR__ . '/../partials/action-error.php';
    return ob_get_clean();
  }
}
