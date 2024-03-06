<?php

namespace Ada_Aba\Admin\Controllers;

use Ada_Aba\Admin\Services\Syllabus_Edit_Service;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Syllabus;
use Ada_Aba\Includes\Dto\Syllabus\Syllabus_Scalar;
use function Ada_Aba\Admin\Fragments\Courses\get_courses_fragment;

use \WP_REST_Server;
use \WP_Error;

class Course_Lessons_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'course-lessons';
  }

  // Register our routes.
  public function register_routes() {
    register_rest_route($this->namespace, '/' . $this->resource_name, array(
      array(
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => array($this, 'add'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<slug>[\w\d]+)', array(
      array(
        'methods'  => WP_REST_Server::DELETABLE,
        'callback' => array($this, 'remove'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<slug>[\w\d]+)/move_up', array(
      array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => array($this, 'move_up'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<slug>[\w\d]+)/move_down', array(
      array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => array($this, 'move_down'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name . '/(?P<slug>[\w\d]+)/toggle_optional', array(
      array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => array($this, 'toggle_optional'),
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

  public function add($request)
  {
    $course_slug = $request->get_param('course');
    $lesson_slug = $request->get_param('lesson');
    $syllabus = Syllabus::create_by_slug($course_slug, $lesson_slug);
    $syllabus->insert();
    return rest_ensure_response(new Syllabus_Scalar($syllabus));
  }

  public function remove($request)
  {
    $slug = $request['slug'];
    Core::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $syllabus = Syllabus::get_by_slug($slug);
    if ($syllabus) {
      $syllabus->remove();
    }
    return rest_ensure_response(new Syllabus_Scalar($syllabus));
  }

  public function move_up($request)
  {
    $slug = $request['slug'];
    Core::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $service = Syllabus_Edit_Service::get_by_member_syllabus_slug($slug);
    $service->move_up($slug);
    $syllabus = Syllabus::get_by_slug($slug);

    return rest_ensure_response(new Syllabus_Scalar($syllabus));
  }

  public function move_down($request)
  {
    $slug = $request['slug'];
    Core::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $service = Syllabus_Edit_Service::get_by_member_syllabus_slug($slug);
    $service->move_down($slug);
    $syllabus = Syllabus::get_by_slug($slug);

    return rest_ensure_response(new Syllabus_Scalar($syllabus));
  }

  public function toggle_optional($request)
  {
    $slug = $request['slug'];
    Core::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $syllabus = Syllabus::get_by_slug($slug);
    if ($syllabus) {
      $syllabus->toggle_optional();
    }

    return rest_ensure_response(new Syllabus_Scalar($syllabus));
  }
}
