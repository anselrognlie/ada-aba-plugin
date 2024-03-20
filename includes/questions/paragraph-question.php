<?php

namespace Ada_Aba\Includes\Questions;

class Paragraph_Question extends Question_Base
{
  public function __construct(
    $slug,
    $prompt,
    $description
  )
  {
    parent::__construct($slug, $prompt, $description);
  }

  public function get_builder()
  {
    return new Paragraph_Question_Builder();
  }
}