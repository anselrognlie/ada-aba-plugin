<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Reports\Survey_Report_Builder;
use Ada_Aba\Includes\Action\Keys;
use Ada_Aba\Includes\Reports\Report_Builder_Factory;

use function Ada_Aba\Public\Action\Links\redirect_to_registration_page;

class Report_Workflow extends Workflow_Base
{
  private $load_handlers;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      Keys\REPORT => array($this, 'handle_report'),
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

  private function handle_report()
  {
    // Make sure the user is actually an admin. Otherwise redirect to the registration page.
    if (!current_user_can('manage_options')) {
      redirect_to_registration_page();
      exit;
    }

    $report_type = $_GET[Keys\REPORT];
    $factory = new Report_Builder_Factory();
    $report_builder = $factory->create($report_type);
    $report = $report_builder->build($this->plugin_name, $_GET);

    $content = $report->get_content();

    header('Cache-Control: private');
    header('Content-Type: ' . $report->get_content_type());
    header('Content-Length: ' . strlen($content));
    header('Content-Disposition: attachment; filename=' . $report->get_filename());

    // Output file.
    echo $content;

    exit;
  }

}
