<?php

namespace Ada_Aba\Admin\Controllers;

use Ada_Aba\Includes\Dto\Survey_Question\Survey_Question_Scalar;
use Ada_Aba\Includes\Models\Survey_Question;
use Ada_Aba\Includes\Services\Survey_Question_Edit_Service;
use Ada_Aba\Includes\Services\Survey_Question_Service;
use \WP_REST_Server;
use \WP_Error;

class Survey_Questions_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'survey-questions';
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
    $survey_slug = $request->get_param('survey');
    $question_slug = $request->get_param('question');
    $service = new Survey_Question_Service();
    $survey_question = $service->create_by_slugs($survey_slug, $question_slug);
    $survey_question->insert();
    return rest_ensure_response(new Survey_Question_Scalar($survey_question));
  }

  public function remove($request)
  {
    $slug = $request['slug'];

    $survey_question = Survey_Question::get_by_slug($slug);
    if ($survey_question) {
      $survey_question->remove();
    }
    return rest_ensure_response(new Survey_Question_Scalar($survey_question));
  }

  public function move_up($request)
  {
    $slug = $request['slug'];

    $service = new Survey_Question_Edit_Service();
    $service->move_up($slug);
    $survey_question = Survey_Question::get_by_slug($slug);

    return rest_ensure_response(new Survey_Question_Scalar($survey_question));
  }

  public function move_down($request)
  {
    $slug = $request['slug'];

    $service = new Survey_Question_Edit_Service();
    $service->move_down($slug);
    $survey_question = Survey_Question::get_by_slug($slug);

    return rest_ensure_response(new Survey_Question_Scalar($survey_question));
  }

  public function toggle_optional($request)
  {
    $slug = $request['slug'];

    $survey_question = Survey_Question::get_by_slug($slug);
    if ($survey_question) {
      $survey_question->toggle_optional();
    }

    return rest_ensure_response(new Survey_Question_Scalar($survey_question));
  }
}
