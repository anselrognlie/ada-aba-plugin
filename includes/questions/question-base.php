<?php

namespace Ada_Aba\Includes\Questions;

use Ada_Aba\Includes\Models\Question;

abstract class Question_Base
{
  private $id;
  private $slug;
  private $prompt;
  private $description;

  public abstract function get_builder();

  protected function __construct(
    $id,
    $slug,
    $prompt,
    $description
  ) {
    $this->id = $id;
    $this->slug = $slug;
    $this->prompt = $prompt;
    $this->description = $description;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getSlug()
  {
    return $this->slug;
  }

  public function getPrompt()
  {
    return $this->prompt;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public static function get_by_slug($slug)
  {
    $model = Question::get_by_slug($slug);
    if (!$model) {
      return null;
    }

    return self::create_from_model($model);
  }

  private static function create_from_model($model)
  {
    $class = $model->getBuilder();

    $builder = new $class;
    $question = $builder->build($model);

    return $question;
  }
}
