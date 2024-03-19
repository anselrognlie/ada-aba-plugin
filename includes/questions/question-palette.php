<?php

namespace Ada_Aba\Includes\Questions;

class Question_Palette
{
  private $builders;

  public function __construct()
  {
    $this->builders = array(
      new No_Response_Question_Builder(),
    );
  }

  public function getBuilders()
  {
    return $this->builders;
  }

  public function get_builder_by_slug($slug)
  {
    foreach ($this->builders as $builder) {
      if ($builder->get_slug() === $slug) {
        return $builder;
      }
    }
    return null;
  }
}