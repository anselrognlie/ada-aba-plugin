<?php

namespace Ada_Aba\Public\Controllers;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Options;
use Ada_Aba\Includes\Reports\Progress_Table_Builder;
use \WP_REST_Server;
use \WP_Error;

class Progress_Report_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'progress-report';
  }

  // Register our routes.
  public function register_routes() {
    register_rest_route($this->namespace, '/' . $this->resource_name, array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'get'),
      ),
    ));
  }

  public function get($request)
  {
    header( "Access-Control-Allow-Origin: *" );

    $api_key = $request->get_param('api_key');
    $options = Options::get_options();
    $set_api_key = $options->get_api_key();
    Core::log('api_key: ' . $api_key);
    Core::log('set_api_key: ' . $set_api_key);
    if ($api_key !== $set_api_key) {
      return new WP_Error('unauthorized', 'Unauthorized', array('status' => 401));
    }

    $course = Course::get_active_course();
    if (!$course) {
      return new WP_Error('no active course', 'no active course', array('status' => 404));
    }

    $progress_table_builder = new Progress_Table_Builder($this->plugin_name);
    $lines = $progress_table_builder->build($course);

    return rest_ensure_response([ 'lines' => $lines ]);
  }
}