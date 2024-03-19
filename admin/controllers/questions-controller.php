<?php

namespace Ada_Aba\Admin\Controllers;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Dto\Question\Question_Scalar;
use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Includes\Services\Syllabus_Edit_Service;
use function Ada_Aba\Admin\Fragments\Questions\get_questions_fragment;

use \WP_REST_Server;
use \WP_Error;

class Questions_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'questions';
  }

  // Register our routes.
  public function register_routes() {
    register_rest_route($this->namespace, '/' . $this->resource_name, array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'index'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
      // array(
      //   'methods'  => WP_REST_Server::CREATABLE,
      //   'callback' => array($this, 'add'),
      //   'permission_callback' => array($this, 'permissions_check'),
      // ),
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
      // array(
      //   'methods'  => WP_REST_Server::EDITABLE,
      //   'callback' => array($this, 'update'),
      //   'permission_callback' => array($this, 'permissions_check'),
      // ),
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
    $questions = Question::all();

    $response = array_map(function ($question) {
      return new Question_Scalar($question);
    }, $questions);

    return rest_ensure_response($response);
  }

  public function get($request)
  {
    $slug = $request['slug'];

    $question = Question::get_by_slug($slug);
    return rest_ensure_response(new Question_Scalar($question));
  }

  public function delete($request)
  {
    $slug = $request['slug'];

    $question = Question::get_by_slug($slug);
    if ($question) {
      $question->delete();
    }
    return rest_ensure_response(new Question_Scalar($question));
  }
}