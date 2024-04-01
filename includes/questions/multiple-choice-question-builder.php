<?php

namespace Ada_Aba\Includes\Questions;

class Multiple_Choice_Question_Builder extends With_Options_Question_Builder
{
  protected function buildDerived($id, $slug, $prompt, $description, $options, $show_other, $other_label)
  {
    return new Multiple_Choice_Question(
      $id,
      $slug,
      $prompt,
      $description,
      $options,
      $show_other,
      $other_label,
    );
  }

  public function get_display_name()
  {
    return 'Multiple Choice';
  }

  public function get_slug()
  {
    return 'multiple-choice';
  }

  protected function get_control_type()
  {
    return 'radio';
  }

  public function get_response($slug, $data) {
    if (array_key_exists($slug, $data)) {
      $value = $data[$slug];
      $other_key = $slug . '-other';

      if ($value === 'other' && array_key_exists($other_key, $data)) {
        $value = $data[$other_key];
      }

      return $value;
    } else {
      return '';
    }
  }

  public function is_response_valid($slug, $data) {
    if (array_key_exists($slug, $data)) {
      $value = $data[$slug];
      $other_key = $slug . '-other';

      if ($value === 'other') {
        if (array_key_exists($other_key, $data) && !empty($data[$other_key])) {
          return true;
        } else {
          // other was selected but no value was provided
          return false;
        }
      }

      return $value;
    }
    
    return true;
  }
}
