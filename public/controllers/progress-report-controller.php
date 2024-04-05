<?php

namespace Ada_Aba\Public\Controllers;

use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Reports\Progress_Table_Builder;
use \WP_REST_Server;
use \WP_Error;

class Progress_Report_Controller extends Public_Restricted_Controller
{
  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name, 'progress-report');
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
    $course = Course::get_active_course();
    if (!$course) {
      return new WP_Error('no active course', 'no active course', array('status' => 404));
    }

    $progress_table_builder = new Progress_Table_Builder($this->plugin_name);
    $lines = $progress_table_builder->build($course);

    return rest_ensure_response(['lines' => $lines]);
  }
}
