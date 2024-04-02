<?php

namespace Ada_Aba\Includes\Reports;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Action\Keys;

class Progress_Report_Builder extends Report_Builder_Base
{
  function build($plugin_name, $params)
  {
    $course_slug = Core::safe_key($params, Keys\COURSE);

    return new Progress_Report($plugin_name, $course_slug);
  }
}
