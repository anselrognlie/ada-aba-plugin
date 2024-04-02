<?php

namespace Ada_Aba\Includes\Reports;

abstract class Report_Builder_Base
{
  abstract function build($plugin_name, $params);
}