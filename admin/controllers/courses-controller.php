<?php

namespace Ada_Aba\Admin\Controllers;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Dto\Course\Course_Scalar;
use Ada_Aba\Includes\Models\Course;

use function Ada_Aba\Admin\Fragments\Courses\get_courses_fragment;

use \WP_REST_Server;
use \WP_Error;

class Courses_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'courses';
  }

  // Register our routes.
  public function register_routes() {
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

    register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<slug>[\w\d]+)/lessons', array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'get_lessons'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));
  }

  /**
   * Check permissions for the posts.
   *
   * @param WP_REST_Request $request Current request.
   */
  public function permissions_check( $request ) {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html('You do not have permissions to access this resource.'), array('status' => 401));
    }
    return true;
  }

  public function index($request)
  {
    $courses = Course::all();

    $serialized_courses = array_map(function ($course) {
      return new Course_Scalar($course);
    }, $courses);

    $response = array(
      'json' => $serialized_courses,
    );

    // error_log(print_r($request->get_headers(), true));
    if ($request->get_header('accept') === 'text/html') {
      $html_courses = array_map(function ($course) {
        return get_courses_fragment($course);
      }, $courses);

      $response['html'] = implode($html_courses);
    }

    return rest_ensure_response($response);
  }

  public function get($request)
  {
    $slug = $request['slug'];

    $course = Course::get_by_slug($slug);
    return rest_ensure_response(new Course_Scalar($course));
  }

  public function add($request)
  {
    $course = Course::create($request->get_param('name'));
    $course->insert();
    return rest_ensure_response(new Course_Scalar($course));
  }

  public function delete($request)
  {
    $slug = $request['slug'];

    $course = Course::get_by_slug($slug);
    if ($course) {
      $course->delete();
    }
    return rest_ensure_response(new Course_Scalar($course));
  }

  public function activate($request)
  {
    $slug = $request['slug'];

    $course = Course::activate($slug);

    return rest_ensure_response(new Course_Scalar($course));
  }

  public function update($request)
  {
    $slug = $request['slug'];
    $name = $request->get_param('name');

    $course = Course::get_by_slug($slug);
    if ($course) {
      $course->setName($name);
      $course->update();
    }

    return rest_ensure_response(new Course_Scalar($course));
  }
}