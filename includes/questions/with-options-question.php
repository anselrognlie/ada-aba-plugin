<?php

namespace Ada_Aba\Includes\Questions;

use Ada_Aba\Includes\Core;
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

  protected function render_options($type, $data = [])
  {
    $parsedown = new Parsedown();
    $base_content = parent::render_content($data);
    $key = $this->getSlug();

    // no matter whether a radio or checkbox, the value will be an array
    $value = Core::safe_key($data, $key, null);

    $i = 0;
    $inputs = array_map(function ($option) use ($type, $parsedown, &$i, $key, $value) {
      $pos = $i;
      $i += 1;
      $option_id = "ada-aba-survey-option-$key-$pos";
      $option_html = $parsedown->text($option);

      $checked = false;
      if ($value !== null) {
        $checked = in_array($option, $value);
      }
      
      return $this->get_question_option_fragment($type, $checked, $key, $option, $option_id, $option_html);
    }, $this->options);
    
    $other = '';
    if ($this->show_other) {
      $option_id = "ada-aba-survey-option-$key-other";
      $other_key = "$key-other";
      $other_value = Core::safe_key($data, $other_key, null);

      $checked = false;
      $option = 'other';
      if ($value !== null) {
        $checked = in_array($option, $value);
      }

      $other = $this->get_question_other_fragment($type, $key, $option_id, $checked, $other_value);
    }

    return $this->get_question_fragment($base_content, $inputs, $other);
  }

  private function get_question_fragment($base_content, $options_html, $other_html)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-with-options-fragment.php';
    return ob_get_clean();
  }

  private function get_question_option_fragment($type, $checked, $question_slug, $option, $option_id, $option_html)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-with-options-option-fragment.php';
    return ob_get_clean();
  }

  private function get_question_other_fragment($type, $question_slug, $option_id, $checked, $value)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-with-options-other-fragment.php';
    return ob_get_clean();
  }
}