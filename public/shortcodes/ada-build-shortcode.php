<?php

namespace Ada_Aba\Public\Shortcodes;

use Ada_Aba\Includes\Options;
use Ada_Aba\Public\Workflows\Confirmation_Workflow;
use Ada_Aba\Public\Workflows\Registration_Workflow;

class Ada_Build_Shortcode
{
  private $plugin_name;
  private $workflows = [];

  public function __construct($plugin_name)
  {
    $this->plugin_name = $plugin_name;
  }

  public function run()
  {
    $this->store_host_page();
    $this->register_page_workflows();
    return $this->run_page_workflows();
  }

  private function store_host_page()
  {
    $options = Options::get_options($this->plugin_name);
    $options->set_ada_build_page(get_the_ID());
    $options->save();
  }

  private function register_page_workflows()
  {
    $this->workflows = array(
      new Confirmation_Workflow($this->plugin_name),
      new Registration_Workflow($this->plugin_name),
    );
  }

  private function run_page_workflows()
  {
    foreach ($this->workflows as $workflow) {
      if ($workflow->can_handle_page()) {
        return $workflow->handle_page();
      }
    }
  }
}