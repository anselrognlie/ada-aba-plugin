<?php

namespace Ada_Aba\Admin\Controllers;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Dto\Survey\Survey_Scalar;
use Ada_Aba\Includes\Models\Survey;

use function Ada_Aba\Admin\Fragments\Surveys\get_surveys_fragment;

use \WP_REST_Server;
use \WP_Error;

class Surveys_Controller
{

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name)
  {
    $this->plugin_name = $plugin_name;
    $this->namespace     = "/$plugin_name/v1";
    $this->resource_name = 'surveys';
  }

  // Register our routes.
  public function register_routes()
  {
    register_rest_route($this->namespace, '/' . $this->resource_name, array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'index'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
      array(
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => array($this, 'add'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<slug>[\w\d]+)', array(
      array(
        'methods'  => WP_REST_Server::DELETABLE,
        'callback' => array($this, 'delete'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'get'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
      array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => array($this, 'update'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<slug>[\w\d]+)/activate', array(
      array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => array($this, 'activate'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<slug>[\w\d]+)/questions', array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'get_questions'),
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

  public function index($request)
  {
    $surveys = Survey::all();

    $serialized_surveys = array_map(function ($survey) {
      return new Survey_Scalar($survey);
    }, $surveys);

    $response = array(
      'json' => $serialized_surveys,
    );

    // error_log(print_r($request->get_headers(), true));
    if ($request->get_header('accept') === 'text/html') {
      $html_surveys = array_map(function ($survey) {
        return get_surveys_fragment($survey);
      }, $surveys);

      $response['html'] = join('', $html_surveys);
    }

    return rest_ensure_response($response);
  }

  public function get($request)
  {
    $slug = $request['slug'];

    $survey = Survey::get_by_slug($slug);
    return rest_ensure_response(new Survey_Scalar($survey));
  }

  public function add($request)
  {
    $name = $request->get_param('name');
    $url = $request->get_param('url');

    $survey = Survey::create($name, false, $url);
    $survey->insert();
    return rest_ensure_response(new Survey_Scalar($survey));
  }

  public function delete($request)
  {
    $slug = $request['slug'];

    $survey = Survey::get_by_slug($slug);
    if ($survey) {
      $survey->delete();
    }
    return rest_ensure_response(new Survey_Scalar($survey));
  }

  public function activate($request)
  {
    $slug = $request['slug'];

    $survey = Survey::activate($slug);

    return rest_ensure_response(new Survey_Scalar($survey));
  }

  public function update($request)
  {
    $slug = $request['slug'];
    $name = $request->get_param('name');

    $survey = Survey::get_by_slug($slug);
    if ($survey) {
      $survey->setName($name);
      $survey->update();
    }

    return rest_ensure_response(new Survey_Scalar($survey));
  }
}
