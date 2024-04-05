<?php

namespace Ada_Aba\Includes\Reports;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Services\Survey_Question_Edit_Service;
use Ada_Aba\Includes\Services\Survey_Response_Service;
use Ada_Aba\Parsedown;

class Survey_Report extends Report_Base
{
  private $survey_slug;

  public function __construct($plugin_name, $survey_slug)
  {
    parent::__construct($plugin_name);
    $this->survey_slug = $survey_slug;
  }

  function get_content_type()
  {
    return 'text/csv';
  }

  function get_filename()
  {
    $now = date('Ymd\THis');
    return "survey-report-$now.csv";
  }

  function get_content()
  {
    $survey_table_builder = new Survey_Table_Builder($this->plugin_name);
    $lines = $survey_table_builder->build($this->survey_slug);
    return join(PHP_EOL, array_map('Ada_Aba\Includes\Core::csv_str', $lines));
  }
}
