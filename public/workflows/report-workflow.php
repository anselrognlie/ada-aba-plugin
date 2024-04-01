<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Reports\Survey_Report_Builder;
use Ada_Aba\Public\Action\Keys;

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

    // If additional reports are added, we can tell which one to run by the report type.
    $report_type = $_GET[Keys\REPORT];

    // For now, the only report is the survey report
    $survey_id = Core::safe_key($_GET, Keys\SURVEY);
    if (!$survey_id) {
      throw new Aba_Exception('Survey ID is required to generate a report');
    }
    $report_builder = new Survey_Report_Builder($this->plugin_name, $survey_id);

    $content = $report_builder->get_content();

    header('Cache-Control: private');
    header('Content-Type: ' . $report_builder->get_content_type());
    header('Content-Length: ' . strlen($content));
    header('Content-Disposition: attachment; filename=' . $report_builder->get_filename());

    // Output file.
    echo $content;

    exit;
  }

}
