<?php

namespace Ada_Aba\Admin\Controllers\UI;

use Ada_Aba\Includes\Questions\Question_Palette;
use \WP_REST_Server;
use \WP_Error;

class Question_Builders_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'ui/question_builders';
  }

  // Register our routes.
  public function register_routes() {
    register_rest_route($this->namespace, '/' . $this->resource_name  . '/(?P<builder_slug>[-_\w\d]+)/editor', array(
      array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => array($this, 'build_editor'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name  . '/(?P<builder_slug>[-_\w\d]+)/preview', array(
      array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => array($this, 'build_preview'),
        'permission_callback' => array($this, 'permissions_check'),
      ),
    ));

    register_rest_route($this->namespace, '/' . $this->resource_name  . '/(?P<builder_slug>[-_\w\d]+)/save', array(
      array(
        'methods'  => WP_REST_Server::EDITABLE,
        'callback' => array($this, 'save_question'),
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

  public function build_editor($request)
  {
    $slug = $request['builder_slug'];

    $palette = new Question_Palette();
    $builder = $palette->get_builder_by_slug($slug);
    $html = $builder->editor();

    $response = ['html' => $html];

    return rest_ensure_response($response);
  }

  public function build_preview($request)
  {
    $slug = $request['builder_slug'];

    $palette = new Question_Palette();
    $builder = $palette->get_builder_by_slug($slug);
    $html = $builder->preview($request->get_json_params());

    $response = ['html' => $html];

    return rest_ensure_response($response);
  }

  public function save_question($request)
  {
    $slug = $request['builder_slug'];

    $palette = new Question_Palette();
    $builder = $palette->get_builder_by_slug($slug);
    [$id, $question_slug] = $builder->save($request->get_json_params());
    $html = $builder->preview($request->get_json_params());

    $response = [
      'data' => [
        'id' => $id,
        'slug' => $question_slug
      ],
      'html' => $html
    ];

    return rest_ensure_response($response);
  }
}