<?php

namespace Ada_Aba\Includes\Reports;

class Error_Log_Report extends Report_Base
{
  private $path;

  public function __construct($plugin_name, $path)
  {
    parent::__construct($plugin_name);
    $this->path = $path;
  }

  function get_content_type()
  {
    return 'text/plain';
  }

  function get_filename()
  {
    $now = date('Ymd\THis');
    return "error-log-$now.log";
  }

  function get_content()
  {
    // this file is located in site/app/public/wp-content/plugins/ada-aba/includes/reports
    // the error log is located in site/logs/php/error.log

    ob_start();
    // include __DIR__ . '/../../../../../../../logs/php/error.log';
    include $this->path;
    return ob_get_clean();
  }
}
