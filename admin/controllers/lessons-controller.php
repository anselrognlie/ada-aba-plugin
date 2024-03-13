<?php

namespace Ada_Aba\Admin\Controllers;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Dto\Lesson\Lesson_Scalar;
use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Includes\Services\Syllabus_Edit_Service;
use function Ada_Aba\Admin\Fragments\Lessons\get_lessons_fragment;

use \WP_REST_Server;
use \WP_Error;

class Lessons_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'lessons';
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
    $lessons = Lesson::all();

    // error_log(print_r($request->get_query_params(), true));

    if (isset($request['excludeCourseSlug'])) {
      $course_slug = $request['excludeCourseSlug'];
      $service = new Syllabus_Edit_Service($course_slug);
      $lessons = $service->get_available_lessons();
    }

    $serialized_lessons = array_map(function ($lesson) {
      return new Lesson_Scalar($lesson);
    }, $lessons);

    $response = array(
      'json' => $serialized_lessons,
    );

    // error_log(print_r($request->get_headers(), true));
    if ($request->get_header('accept') === 'text/html') {
      $html_lessons = array_map(function ($lesson) {
        return get_lessons_fragment($lesson);
      }, $lessons);

      $response['html'] = implode($html_lessons);
    }

    return rest_ensure_response($response);
  }

  public function get($request)
  {
    $slug = $request['slug'];

    $lesson = Lesson::get_by_slug($slug);
    return rest_ensure_response(new Lesson_Scalar($lesson));
  }

  public function add($request)
  {
    $lesson = Lesson::create($request->get_param('name'));
    $lesson->insert();
    return rest_ensure_response(new Lesson_Scalar($lesson));
  }

  public function delete($request)
  {
    $slug = $request['slug'];

    $lesson = Lesson::get_by_slug($slug);
    if ($lesson) {
      $lesson->delete();
    }
    return rest_ensure_response(new Lesson_Scalar($lesson));
  }

  public function update($request)
  {
    $slug = $request['slug'];
    $name = $request->get_param('name');
    error_log(print_r($request->get_params(), true));

    $lesson = Lesson::get_by_slug($slug);
    if ($lesson) {
      $lesson->setName($name);
      $lesson->update();
    }

    return rest_ensure_response(new Lesson_Scalar($lesson));
  }
}