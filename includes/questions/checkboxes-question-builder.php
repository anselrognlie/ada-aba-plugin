<?php

namespace Ada_Aba\Includes\Questions;

class Checkboxes_Question_Builder extends With_Options_Question_Builder
{
  protected function buildDerived($id, $slug, $prompt, $description, $options, $show_other)
  {
    return new Checkboxes_Question(
      $id,
      $slug,
      $prompt,
      $description,
      $options,
      $show_other,
    );
  }

  public function get_display_name()
  {
    return 'Checkboxes';
  }

  public function get_slug()
  {
    return 'checkboxes';
  }

  protected function get_control_type()
  {
    return 'checkbox';
  }

  public function get_response($slug, $data) {
    if (array_key_exists($slug, $data)) {
      $values = $data[$slug];
      $other_key = $slug . '-other';

      if (in_array('other', $values) && array_key_exists($other_key, $data)) {
        $values = array_diff($values, ['other']);
        $values[] = $data[$other_key];
      }

      return join(', ', $values);
    } else {
      return '';
    }
  }
}
