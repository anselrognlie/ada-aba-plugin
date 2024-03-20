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
}
