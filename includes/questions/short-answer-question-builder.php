<?php

namespace Ada_Aba\Includes\Questions;

class Short_Answer_Question_Builder extends Question_Builder_Base
{
  public function build($model)
  {
    $slug = $model->getSlug();
    $prompt = $model->getPrompt();
    $description = $model->getDescription();

    return new Short_Answer_Question(
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

    return new Short_Answer_Question(
      $slug,
      $prompt,
      $description
    );
  }

  public function get_display_name()
  {
    return 'Short Answer';
  }

  public function get_slug()
  {
    return 'short-answer';
  }

  protected function editorDerived($model)
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
}
