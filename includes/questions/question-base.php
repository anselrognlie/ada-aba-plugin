<?php

namespace Ada_Aba\Includes\Questions;

use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Parsedown;

abstract class Question_Base
{
  private $id;
  private $slug;
  private $prompt;
  private $description;

  public abstract function get_builder();
  // protected abstract function render_content();

  protected function __construct(
    $id,
    $slug,
    $prompt,
    $description
  ) {
    $this->id = $id;
    $this->slug = $slug;
    $this->prompt = $prompt;
    $this->description = $description;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getSlug()
  {
    return $this->slug;
  }

  public function getPrompt()
  {
    return $this->prompt;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public static function get_by_slug($slug)
  {
    $model = Question::get_by_slug($slug);
    if (!$model) {
      return null;
    }

    return self::create_from_model($model);
  }

  private static function create_from_model($model)
  {
    $class = $model->getBuilder();

    $builder = new $class;
    $question = $builder->build($model);

    return $question;
  }

  public function render($is_required, $data = [])
  {
    $content = $this->render_content($data);
    $builder_name = $this->get_builder();
    $builder = new $builder_name();
    $builder_slug = $builder->get_slug();
    return $this->get_question_wrapper_fragment($is_required, $builder_slug, $content);
  }

  protected function render_content($data = [])
  {
    $parsedown = new Parsedown();
    $prompt_html = $parsedown->text($this->getPrompt());
    $has_description = $this->getDescription();

    $description_html = $parsedown->text($this->getDescription());
    return $this->get_question_base_fragment($has_description, $prompt_html, $description_html);
  }

  private function get_question_wrapper_fragment($required, $builder_slug, $content)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-wrapper-fragment.php';
    return ob_get_clean();
  }

  private function get_question_base_fragment($has_description, $prompt_html, $description_html)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-base-fragment.php';
    return ob_get_clean();
  }
}
