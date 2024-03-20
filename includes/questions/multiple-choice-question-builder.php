<?php

namespace Ada_Aba\Includes\Questions;

class Multiple_Choice_Question_Builder extends With_Options_Question_Builder
{
  protected function buildDerived($id, $slug, $prompt, $description, $options, $show_other)
  {
    return new Multiple_Choice_Question(
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
}
