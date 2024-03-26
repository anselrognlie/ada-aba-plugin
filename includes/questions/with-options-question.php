<?php

namespace Ada_Aba\Includes\Questions;

use Ada_Aba\Parsedown;

class With_Options_Question extends Question_Base
{
  private $options;
  private $show_other;

  public function __construct(
    $id,
    $slug,
    $prompt,
    $description,
    $options,
    $show_other,
  )
  {
    parent::__construct($id, $slug, $prompt, $description);
    $this->options = $options;
    $this->show_other = $show_other;
  }

  public function getOptions()
  {
    return $this->options;
  }

  public function getShowOther()
  {
    return $this->show_other;
  }

  public function get_builder()
  {
    return new With_Options_Question_Builder();
  }

  protected function render_options($type)
  {
    $parsedown = new Parsedown();
    $base_content = parent::render_content();
    $i = 0;
    $inputs = array_map(function ($option) use ($type, $parsedown, &$i) {
      $pos = $i;
      $i += 1;
      $question_slug = $this->getSlug();
      $option_id = "ada-aba-survey-option-$question_slug-$pos";
      $option_html = $parsedown->text($option);
      return $this->get_question_option_fragment($type, $question_slug, $option, $option_id, $option_html);
    }, $this->options);
    
    $other = '';
    if ($this->show_other) {
      $question_slug = $this->getSlug();
      $option_id = "ada-aba-survey-option-$question_slug-other";
      $other = $this->get_question_other_fragment($type, $question_slug, $option_id);
    }

    return $this->get_question_fragment($base_content, $inputs, $other);
  }

  private function get_question_fragment($base_content, $options_html, $other_html)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-with-options-fragment.php';
    return ob_get_clean();
  }

  private function get_question_option_fragment($type, $question_slug, $option, $option_id, $option_html)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-with-options-option-fragment.php';
    return ob_get_clean();
  }

  private function get_question_other_fragment($type, $question_slug, $option_id)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-with-options-other-fragment.php';
    return ob_get_clean();
  }
}