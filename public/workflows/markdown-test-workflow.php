<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Parsedown;
use Ada_Aba\Public\Challenge_Actions\Register_Action;
use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Action\Links;

class Markdown_Test_Workflow extends Workflow_Base
{
  private $load_handlers;
  private $page_handlers;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      // Keys\VERIFY => array($this, 'handle_verify'),
      // Keys\RESEND => array($this, 'handle_resend'),
    ];
    $this->page_handlers = [
      Keys\MARKDOWN => array($this, 'handle_markdown'),
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

  private function handle_markdown()
  {
    $content = Core::safe_key($_POST, 'md-content', '');
    $parsedown = new Parsedown();
    $rendered = $parsedown->text($content);
    $action = Links\get_markdown_link()

    ?>
    <form method="post" action="<?php echo $action ?>">
    <textarea name="md-content"><?php echo $content ?></textarea>
    <input type="submit" value="Render">
    </form>

    <div>
      <?php echo $rendered ?>
    <?php
  }
}
