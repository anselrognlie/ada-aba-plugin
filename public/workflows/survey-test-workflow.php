<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Services\Survey_Question_Edit_Service;
use Ada_Aba\Includes\Services\Survey_Question_Service;
use Ada_Aba\Public\Action\Keys;

class Survey_Test_Workflow extends Workflow_Base
{
  private $load_handlers;
  private $page_handlers;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
    $this->load_handlers = [
      // Keys\VERIFY => array($this, 'handle_verify'),
      // Keys\RESEND => array($this, 'handle_resend'),
    ];
    $this->page_handlers = [
      Keys\SURVEY_TEST => array($this, 'handle_survey'),
    ];
  }

  public function can_handle_load_precise()
  {
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

  private function handle_survey()
  {
    $survey_slug = $_GET[Keys\SURVEY_TEST];
    $survey = Survey::get_by_slug($survey_slug);
    if (!$survey) {
      return;
    }

    return $this->render_survey($survey);
  }

  private function render_survey($survey)
  {
    $survey_name = $survey->getName();
    $sqe_service = new Survey_Question_Edit_Service();
    $survey_question_relations = $sqe_service->get_survey_questions($survey->getSlug());
    $questions_html = array_map(function ($survey_question_relation) {
      $model = $survey_question_relation->getQuestion();
      $builder_class = $model->getBuilder();
      $builder = new $builder_class;
      $question = $builder->build($model);
      $optional = $survey_question_relation->isOptional();
      return $question->render(!$optional);
    }, $survey_question_relations);


    return $this->get_survey_form($survey_name, $questions_html);
  }

  private function get_survey_form($survey_name, $questions_html)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form.php';
    return ob_get_clean();
  }
}
