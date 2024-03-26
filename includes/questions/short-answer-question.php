<?php

namespace Ada_Aba\Includes\Questions;

class Short_Answer_Question extends Question_Base
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
    return new Short_Answer_Question_Builder();
  }

  protected function render_content()
  {
    $base_content = parent::render_content();
    return $this->get_question_fragment($base_content, $this->getSlug());
  }

  private function get_question_fragment($base_content, $question_slug)
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form-question-short-answer-fragment.php';
    return ob_get_clean();
  }
}
