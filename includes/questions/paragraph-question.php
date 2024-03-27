<?php

namespace Ada_Aba\Includes\Questions;

use Ada_Aba\Includes\Core;

class Paragraph_Question extends Question_Base
{
  public function __construct(
    $id,
    $slug,
    $prompt,
    $description
  ) {
    parent::__construct($id, $slug, $prompt, $description);
  }

  public function get_builder()
  {
    return new Paragraph_Question_Builder();
  }

  protected function render_content($data = [])
  {
    $base_content = parent::render_content($data);
    $key = $this->getSlug();
    $value = Core::safe_key($data, $key, null);
    return $this->get_question_fragment($base_content, $this->getSlug(), $value);
  }

  private function get_question_fragment($base_content, $question_slug, $value)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-paragraph-fragment.php';
    return ob_get_clean();
  }
}
