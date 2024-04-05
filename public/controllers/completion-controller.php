<?php

namespace Ada_Aba\Public\Controllers;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Dto\Lesson\Lesson_Scalar;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Includes\Services\Syllabus_Edit_Service;
use Ada_Aba\Public\Challenge_Actions\Action_Context;
use Ada_Aba\Public\Challenge_Actions\Complete_Lesson_Action;
use \WP_REST_Server;
use \WP_Error;

class Completion_Controller {

  private $plugin_name;
  private $namespace;
  private $resource_name;

  public function __construct($plugin_name) {
      $this->plugin_name = $plugin_name;
      $this->namespace     = "/$plugin_name/v1";
      $this->resource_name = 'completion';
  }

  // Register our routes.
  public function register_routes() {
    register_rest_route($this->namespace, '/' . $this->resource_name, array(
      array(
        'methods'  => WP_REST_Server::CREATABLE,
        'callback' => array($this, 'add'),
      ),
    ));
  }

  public function add($request)
  {
    header( "Access-Control-Allow-Origin: *" );

    $lesson = Lesson::get_by_slug($request->get_param('lesson'));
    if (!$lesson) {
      return new WP_Error('lesson_not_found', 'Lesson not found', array('status' => 404));
    }

    $learner = Learner::get_by_slug($request->get_param('u'));
    if (!$learner) {
      return new WP_Error('learner_not_found', 'Learner not found', array('status' => 404));
    }

    $action = Complete_Lesson_Action::create(
      $learner->getEmail(), 
      $lesson->getSlug(), 
      $learner->getSlug(),
    );

    try {
      $action->run(context: Action_Context::API);
    } catch (Aba_Exception $e) {
      Core::log($e);
      return new WP_Error('action_failed', $e->getMessage(), array('status' => 500));
    }

    return rest_ensure_response([ 'status' => 'ok' ]);
  }
}