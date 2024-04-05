<?php

namespace Ada_Aba\Public\Controllers;

use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Reports\Survey_Table_Builder;
use \WP_REST_Server;
use \WP_Error;

class Survey_Report_Controller extends Public_Restricted_Controller
{
  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name, 'survey-report');
  }

  // Register our routes.
  public function register_routes()
  {
    register_rest_route($this->namespace, '/' . $this->resource_name, array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => $this->get(...),
        'permission_callback' => $this->permissions_check(...),
      ),
    ));
  }

  protected function get($request)
  {
    $survey = Survey::get_active_survey();
    if (!$survey) {
      return new WP_Error('no active survey', 'no active survey', array('status' => 404));
    }

    $survey_table_builder = new Survey_Table_Builder($this->plugin_name);
    $lines = $survey_table_builder->build($survey->getSlug());

    return rest_ensure_response([ 'lines' => $lines ]);
  }
}
