<?php

namespace Ada_Aba\Admin\Controllers\UI;

use Ada_Aba\Admin\Services\Syllabus_Edit_Service;
use Ada_Aba\Includes\Core;
use function Ada_Aba\Admin\Fragments\Course_Lessons\get_available_lessons_fragment;
use function Ada_Aba\Admin\Fragments\Course_Lessons\get_course_lessons_fragment;

use \WP_REST_Server;
use \WP_Error;

class Syllabus_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'ui/syllabus';
  }

  // Register our routes.
  public function register_routes() {
    register_rest_route($this->namespace, '/' . $this->resource_name  . '/(?P<slug>[\w\d]+)/available_lessons', array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'available_lessons'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name  . '/(?P<slug>[\w\d]+)/course_lessons', array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'course_lessons'),
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

  public function available_lessons($request)
  {
    $slug = $request['slug'];
    Core::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $service = new Syllabus_Edit_Service($slug);
    $lessons = $service->getAvailableLessons();

    $html = array_map(function ($course_lesson) {
      return get_available_lessons_fragment($course_lesson);
    }, $lessons);

    $response = ['html' => implode($html)];

    return rest_ensure_response($response);
  }

  public function course_lessons($request)
  {
    $slug = $request['slug'];
    Core::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $service = new Syllabus_Edit_Service($slug);
    $course_lessons = $service->getCourseLessons();

    $html = array_map(function ($course_lesson) {
      return get_course_lessons_fragment($course_lesson);
    }, $course_lessons);

    $response = ['html' => implode($html)];

    return rest_ensure_response($response);
  }
}