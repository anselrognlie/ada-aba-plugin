<?php

namespace Ada_Aba\Includes\Questions;

class Short_Answer_Question extends Question_Base
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
    return new Short_Answer_Question_Builder();
  }
}