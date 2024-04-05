<?php

namespace Ada_Aba\Public\Controllers;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Options;
use WP_Error;

class Public_Restricted_Controller
{
  protected $plugin_name;
  protected $namespace;
  protected $resource_name;

  protected function __construct($plugin_name, $resource_name)
  {
    $this->plugin_name = $plugin_name;
    $this->namespace     = "/$plugin_name/v1";
    $this->resource_name = $resource_name;
  }

  protected function enable_cors() {
    header("Access-Control-Allow-Origin: *");
  }

  protected function permissions_check($request)
  {
    $this->enable_cors();

    $api_key = $request->get_param('api_key');
    $options = Options::get_options();
    $set_api_key = $options->get_api_key();
    if ($api_key !== $set_api_key) {
      return new WP_Error('unauthorized', 'Unauthorized', array('status' => 401));
    }

    return true;
  }
}