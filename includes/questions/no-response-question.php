<?php

namespace Ada_Aba\Includes\Questions;

class No_Response_Question extends Question_Base
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
    return new No_Response_Question_Builder();
  }
}