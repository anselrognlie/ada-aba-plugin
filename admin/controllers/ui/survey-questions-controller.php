<?php

namespace Ada_Aba\Admin\Controllers\UI;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Dto\Question\Question_List_Item;
use Ada_Aba\Includes\Dto\Survey_Question\Survey_Question_List_Item;
use Ada_Aba\Includes\Services\Survey_Question_Edit_Service;
use function Ada_Aba\Admin\Fragments\Survey_Questions\get_available_questions_fragment;
use function Ada_Aba\Admin\Fragments\Survey_Questions\get_survey_questions_fragment;

use \WP_REST_Server;
use \WP_Error;

class Survey_Questions_Controller
{

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name)
  {
    $this->plugin_name = $plugin_name;
    $this->namespace     = "/$plugin_name/v1";
    $this->resource_name = 'ui/surveys';
  }

  // Register our routes.
  public function register_routes()
  {
    register_rest_route($this->namespace, '/' . $this->resource_name  . '/(?P<slug>[\w\d]+)/available_questions', array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'available_questions'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name  . '/(?P<slug>[\w\d]+)/survey_questions', array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'survey_questions'),
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

  public function available_questions($request)
  {
    $slug = $request['slug'];

    $service = new Survey_Question_Edit_Service();
    $questions = $service->get_available_questions($slug);

    $html = array_map(function ($question) {
      return get_available_questions_fragment(new Question_List_Item($question));
    }, $questions);

    $response = ['html' => join('', $html)];

    return rest_ensure_response($response);
  }

  public function survey_questions($request)
  {
    $slug = $request['slug'];

    $service = new Survey_Question_Edit_Service();
    $survey_question_relations = $service->get_survey_questions($slug);

    $html = array_map(function ($survey_question_relation) {
      return get_survey_questions_fragment(new Survey_Question_List_Item($survey_question_relation));
    }, $survey_question_relations);

    $response = ['html' => join('', $html)];

    return rest_ensure_response($response);
  }
}
