<?php

namespace Ada_Aba\Includes\Questions;

class Paragraph_Question_Builder extends Question_Builder_Base
{
  public function build($model)
  {
    $slug = $model->getSlug();
    $prompt = $model->getPrompt();
    $description = $model->getDescription();

    return new Paragraph_Question(
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

    return new Paragraph_Question(
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
    return 'paragraph';
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
