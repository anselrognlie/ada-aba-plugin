<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Models\Surveyed_Learner;
use Ada_Aba\Includes\Object_Session;
use Ada_Aba\Includes\Options;
use Ada_Aba\Includes\Services\Survey_Question_Edit_Service;
use Ada_Aba\Includes\Services\Survey_Question_Service;
use Ada_Aba\Includes\Services\Survey_Response_Service;
use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Data_Adapters\Survey_State_Adapter;

use function Ada_Aba\Public\Action\Links\get_progress_link;
use function Ada_Aba\Public\Action\Links\get_survey_link;
use function Ada_Aba\Public\Action\Links\redirect_to_page;
use function Ada_Aba\Public\Action\Links\redirect_to_progress_page;

class Survey_Workflow extends Workflow_Base
{
  private $load_handlers;
  private $page_handlers;
  private $views;
  private $learner;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      Keys\SURVEY => array($this, 'handle_survey_load'),
    ];
    $this->page_handlers = [
      Keys\SURVEY => array($this, 'handle_survey'),
    ];
    $this->views = [
      array($this, 'show_welcome'),
      array($this, 'show_survey'),
      array($this, 'show_thanks'),
    ];
  }

  private function has_learner()
  {
    $learner_slug = Core::safe_key($_REQUEST, Keys\USER, '');
    $this->learner = Learner::get_by_slug($learner_slug);
    return ((bool)$this->learner);
  }

  public function can_handle_load_precise()
  {
    // make sure we have a learner
    if (!$this->has_learner()) {
      return false;
    }

    foreach ($this->load_handlers as $key => $_) {
      if ($this->is_in_get($key)) {
        return true;
      }
    }
    return false;
  }

  public function handle_load()
  {
    foreach ($this->load_handlers as $key => $handler) {
      if ($this->is_in_get($key)) {
        call_user_func($handler);
        return;
      }
    }
  }

  public function can_handle_page()
  {
    if (!$this->has_learner()) {
      return false;
    }

    foreach ($this->page_handlers as $key => $_) {
      if ($this->is_in_get($key)) {
        return true;
      }
    }
    return false;
  }

  public function handle_page()
  {
    foreach ($this->page_handlers as $key => $handler) {
      if ($this->is_in_get($key)) {
        return call_user_func($handler);
      }
    }
  }

  private function get_view_index()
  {
    $page = $_GET[Keys\SURVEY];
    $index = intval($page);
    if ($index < 0 || $index >= count($this->views)) {
      return 0;
    }

    return $index;
  }

  private function handle_survey_load()
  {
    $survey = Survey::get_active_survey();
    $learner_slug = $this->learner->getSlug();

    if ((!$survey) || Surveyed_Learner::contains($learner_slug)) {
      redirect_to_progress_page($learner_slug);
    }

    if ($this->is_post()) {
      $this->handle_survey_post();
    }
  }

  private function handle_survey()
  {
    $view_index = $this->get_view_index();
    $show_function = $this->views[$view_index];
    return call_user_func($show_function);
  }

  private function show_welcome()
  {
    $next_link = $this->get_next_view_link();
    return $this->get_welcome_content($next_link);
  }

  private function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

  private function get_form_state()
  {
    return $_POST['form_state'];
  }

  private function show_survey()
  {
    return $this->handle_survey_form();
  }

  private function handle_survey_form()
  {
    $survey = Survey::get_active_survey();
    if (!$survey) {
      return;
    }

    return $this->render_survey($survey);
  }

  private function get_next_view_link()
  {
    $view_index = $this->get_view_index();
    $next_index = $view_index + 1;
    if ($next_index >= count($this->views)) {
      return get_progress_link($this->learner->getSlug());
    }

    return get_survey_link($this->learner->getSlug(), $next_index);
  }

  private function handle_survey_post()
  {
    $form_state = $this->get_form_state();
    $session = new Object_Session($this->options->get_private_key());
    $session->load($form_state);
    $adapter = new Survey_State_Adapter($session);
    $survey_slug = $adapter->get_survey_slug();

    $sr_service = new Survey_Response_Service();
    try
    {
      $sr_service->process_survey_responses($survey_slug, $this->learner->getSlug(), $_POST);
      $next_link = $this->get_next_view_link();
      redirect_to_page($next_link);
    } catch (Aba_Exception $e) {
      Core::log($e->getMessage());
      $_POST['error']  = 'An error occurred while submitting the survey.'
        . ' Please answer all required (*) questions and ensure that'
        . ' any selected options with space for additional detail have values provided.';
    }
  }

  private function show_thanks()
  {
    $next_link = $this->get_next_view_link();
    return $this->get_thanks_content($next_link);
  }

  private function render_survey($survey)
  {
    $survey_slug = $survey->getSlug();

    $private_key = $this->options->get_private_key();
    $session = new Object_Session($private_key);
    $adapter = new Survey_State_Adapter($session);
    $adapter->set_survey_slug($survey_slug);
    $state = $session->save();

    $sr_service = new Survey_Response_Service();
    return $sr_service->render_survey($survey_slug, $state, $_POST);
  }

  private function get_welcome_content($next_link)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-welcome.php';
    return ob_get_clean();
  }

  private function get_thanks_content($next_link)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-thanks.php';
    return ob_get_clean();
  }
}
