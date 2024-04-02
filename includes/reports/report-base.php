<?php

namespace Ada_Aba\Includes\Reports;

abstract class Report_Base
{
  protected $plugin_name;

  public function __construct($plugin_name)
  {
    $this->plugin_name = $plugin_name;
  }

  abstract function get_content_type();
  abstract function get_filename();
  abstract function get_content();
}