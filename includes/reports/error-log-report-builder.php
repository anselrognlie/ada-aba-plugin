<?php

namespace Ada_Aba\Includes\Reports;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Action\Keys;

class Error_Log_Report_Builder extends Report_Builder_Base
{
  function build($plugin_name, $params)
  {
    $path = Core::safe_key($params, Keys\PATH);
    $path = urldecode($path);

    return new Error_Log_Report($plugin_name, $path);
  }
}
