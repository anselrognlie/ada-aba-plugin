<?php

namespace Ada_Aba\Includes\Questions;

class No_Response_Question_Builder extends Question_Builder_Base
{
  public function build($model)
  {
    $slug = $model->getSlug();
    $prompt = $model->getPrompt();
    $description = $model->getDescription();

    return new No_Response_Question(
      $slug,
      $prompt,
      $description
    );
  }

  public function create()
  {
    $slug = null;
    $prompt = '';
    $description = '';

    return new No_Response_Question(
      $slug,
      $prompt,
      $description
    );
  }

  public function get_display_name()
  {
    return 'Paragraph';
  }

  public function get_slug()
  {
    return 'no-response';
  }

  protected function editorDerived()
  {
  }

  protected function previewDerived($request)
  {
  }

  protected function getData($request)
  {
    return [];
  }
}
