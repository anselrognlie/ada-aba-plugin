<?php

namespace Ada_Aba\Admin\Controllers\UI;

use Ada_Aba\Includes\Dto\Question\Question_List_Item;
use Ada_Aba\Includes\Models\Question;
use \WP_REST_Server;
use \WP_Error;

use function Ada_Aba\Admin\Fragments\Questions\get_questions_fragment;

class Questions_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'ui/questions';
  }

  // Register our routes.
  public function register_routes() {
    register_rest_route($this->namespace, '/' . $this->resource_name  . '/', array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'index'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    // register_rest_route($this->namespace, '/' . $this->resource_name  . '/(?P<slug>[-_\w\d]+)', array(
    //   array(
    //     'methods'  => WP_REST_Server::READABLE,
    //     'callback' => array($this, 'get'),
    //     'permission_callback' => array($this, 'permissions_check'),
    //   ),
    // ));
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

    $serialized_questions = array_map(function ($question) {
      return get_questions_fragment(new Question_List_Item($question));
    }, $questions);

    $response = array(
      'html' => join('', $serialized_questions),
    );

    return rest_ensure_response($response);
  }
}