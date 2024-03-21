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

  public function render($is_required)
  {
    $content = $this->render_content();
    $required_class = $is_required ? ' ada-aba-survey-survey-question-required' : '';
    return '<div class="ada-aba-survey-survey-question' . $required_class . '">' . $content . '</div>';
  }

  protected function render_content()
  {
    $parsedown = new Parsedown();
    $prompt_html = $parsedown->text($this->getPrompt());
    $description_html = $parsedown->text($this->getDescription());
    return "
        <div class='ada-aba-survey-survey-question-prompt'>
          $prompt_html
        </div>
        <div class='ada-aba-survey-survey-question-description'>
          $description_html
        </div>
    ";
  }
}
