<?php

namespace Ada_Aba\Includes\Questions;

class Checkboxes_Question extends With_Options_Question
{
  public function __construct(
    $id,
    $slug,
    $prompt,
    $description,
    $options,
    $show_other,
  ) {
    parent::__construct($id, $slug, $prompt, $description, $options, $show_other);
  }

  public function get_builder()
  {
    return new Checkboxes_Question_Builder();
  }

  protected function render_content()
  {
    return parent::render_options('checkbox');
  }
}
