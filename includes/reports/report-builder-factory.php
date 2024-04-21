<?php

namespace Ada_Aba\Includes\Reports;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Action\Keys;

class Report_Builder_Factory
{
  private $builders;

  public function __construct()
  {
    $this->builders = array(
      Keys\SURVEY_REPORT => 'Ada_Aba\Includes\Reports\Survey_Report_Builder',
      Keys\PROGRESS_REPORT => 'Ada_Aba\Includes\Reports\Progress_Report_Builder',
      Keys\ERROR_LOG_REPORT => 'Ada_Aba\Includes\Reports\Error_Log_Report_Builder',
    );
  }

  public function create($report_type)
  {
    if (!array_key_exists($report_type, $this->builders)) {
      throw new Aba_Exception('Invalid report type');
    }

    $class_name = $this->builders[$report_type];
    return new $class_name();
  }
}