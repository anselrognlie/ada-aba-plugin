<?php

namespace Ada_Aba\Includes\Questions;

class No_Response_Question_Builder extends Question_Builder_Base
{
  public function build($model)
  {
    $id = $model->getId();
    $slug = $model->getSlug();
    $prompt = $model->getPrompt();
    $description = $model->getDescription();

    return new No_Response_Question(
      $id,
      $slug,
      $prompt,
      $description
    );
  }

  public function get_display_name()
  {
    return 'No Response';
  }

  public function get_slug()
  {
    return 'no-response';
  }

  protected function editorDerived($question)
  {
    return '';
  }

  protected function previewDerived($request)
  {
    return '';
  }

  protected function getData($request)
  {
    return [];
  }

  public function gets_response() {
    return false;
  }
}
