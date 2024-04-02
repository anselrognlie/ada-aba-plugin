<?php

namespace Ada_Aba\Includes\Reports;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Action\Keys;

class Survey_Report_Builder extends Report_Builder_Base
{
  function build($plugin_name, $params)
  {
    $survey_slug = Core::safe_key($params, Keys\SURVEY);

    return new Survey_Report($plugin_name, $survey_slug);
  }
}
