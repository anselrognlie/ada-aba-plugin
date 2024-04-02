<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Action\Keys;
use Ada_Aba\Public\Action\Links;
use Ada_Aba\Public\Challenge_Actions\Action_Base;

class Error_Workflow extends Workflow_Base
{
  private $page_handlers;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->page_handlers = [
      Keys\ERROR => array($this, 'handle_error'),
    ];
  }

  public function can_handle_load_precise()
  {
    return false;
  }

  public function handle_load()
  {
    // no handlers
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

  private function handle_error()
  {
    $error = $_GET[Keys\ERROR];
    return $this->get_error_page($error);
  }

  private function get_error_page($error)
  {
    ob_start();
    include __DIR__ . '/../partials/error-page.php';
    return ob_get_clean();
  }
}
