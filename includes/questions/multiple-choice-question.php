<?php

namespace Ada_Aba\Includes\Questions;

class Multiple_Choice_Question extends With_Options_Question
{
  public function __construct(
    $id,
    $slug,
    $prompt,
    $description,
    $options,
    $show_other,
    $other_label,
  ) {
    parent::__construct($id, $slug, $prompt, $description, $options, $show_other, $other_label);
  }

  public function get_builder()
  {
    return new Multiple_Choice_Question_Builder();
  }

  protected function render_content($data = [])
  {
    // repackage data for this question into an array for consistent handling
    $slug = $this->getSlug();
    if (array_key_exists($slug, $data)) {
      $data[$slug] = [$data[$slug]];
    }
    return parent::render_options('radio', $data);
  }
}
