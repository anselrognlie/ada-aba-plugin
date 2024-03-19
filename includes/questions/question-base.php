<?php

namespace Ada_Aba\Includes\Questions;

abstract class Question_Base
{
  private $slug;
  private $prompt;
  private $description;

  public abstract function get_builder();

  protected function __construct(
    $slug,
    $prompt,
    $description
  ) {
    $this->slug = $slug;
    $this->prompt = $prompt;
    $this->description = $description;
  }

}
