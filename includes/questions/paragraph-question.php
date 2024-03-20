<?php

namespace Ada_Aba\Includes\Questions;

class Paragraph_Question extends Question_Base
{
  public function __construct(
    $id,
    $slug,
    $prompt,
    $description
  )
  {
    parent::__construct($id, $slug, $prompt, $description);
  }

  public function get_builder()
  {
    return new Paragraph_Question_Builder();
  }
}