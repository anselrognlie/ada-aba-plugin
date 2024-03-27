<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Services\Survey_Question_Edit_Service;
use Ada_Aba\Includes\Services\Survey_Question_Service;
use Ada_Aba\Public\Action\Keys;

use function Ada_Aba\Public\Action\Links\get_progress_link;
use function Ada_Aba\Public\Action\Links\get_survey_link;

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
      Keys\SURVEY => function(){},  // nop to prevent progress redirect loop
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

  private function handle_survey()
  {
    $view_index = $this->get_view_index();
    $show_function = $this->views[$view_index];
    return call_user_func($show_function);
  }

  private function show_welcome()
  {
    $next_link = get_survey_link($this->learner->getSlug(), 1);
    return $this->get_welcome_content($next_link);
  }

  private function show_survey()
  {
    $survey = Survey::get_active_survey();
    if (!$survey) {
      return;
    }

    return $this->render_survey($survey);
  }

  private function show_thanks()
  {
    $next_link = get_progress_link($this->learner->getSlug());
    return $this->get_thanks_content($next_link);
  }

  // eventually refactor this to remove duplication with survey test workflow
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
