<?php

namespace Ada_Aba\Includes\Questions;

class With_Options_Question extends Question_Base
{
  private $options;
  private $show_other;

  public function __construct(
    $id,
    $slug,
    $prompt,
    $description,
    $options,
    $show_other,
  )
  {
    parent::__construct($id, $slug, $prompt, $description);
    $this->options = $options;
    $this->show_other = $show_other;
  }

  public function getOptions()
  {
    return $this->options;
  }

  public function getShowOther()
  {
    return $this->show_other;
  }

  public function get_builder()
  {
    return new With_Options_Question_Builder();
  }
}