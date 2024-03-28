<?php

namespace Ada_Aba\Admin\Controllers;

use Ada_Aba\Includes\Core;
use \WP_REST_Server;
use \WP_Error;

class Queries_Controller
{
  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name)
  {
    $this->plugin_name = $plugin_name;
    $this->namespace     = "/$plugin_name/v1";
    $this->resource_name = 'queries';
  }

  // Register our routes.
  public function register_routes()
  {
    register_rest_route($this->namespace, '/' . $this->resource_name, array(
      array(
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => array($this, 'execute'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));
  }

  /**
   * Check permissions for the posts.
   *
   * @param WP_REST_Request $request Current request.
   */
  public function permissions_check($request)
  {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html('You do not have permissions to access this resource.'), array('status' => 401));
    }
    return true;
  }

  public function execute($request)
  {
    $query = $request->get_param('query');

    global $wpdbx;
    // $result = $wpdb->get_results($query, ARRAY_N);
    $result = $wpdbx->get_raw_results($query);
    Core::log(print_r($result, true));

    if ($result === false) {
      return new WP_Error('rest_invalid_query', esc_html('Invalid query'), array('status' => 400));
    }

    return rest_ensure_response($result);
  }
}
