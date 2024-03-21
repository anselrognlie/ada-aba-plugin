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

  protected function render_options($type)
  {
    $base_content = parent::render_content();
    $inputs = array_map(function ($option) use ($type) {
      return '<input type="' . $type . '" name="' . $this->getSlug() . '" value="' . $option . '">' . $option . '<br>';
    }, $this->options);
    
    return join('', [
      $base_content,
      '<div>',
      ...$inputs,
      $this->show_other ? '<input type="' . $type . '" name="' . $this->getSlug() . '" value="other">' . 'Other'
        . '<input type="text" name="' . $this->getSlug() . '">' : '',
      '</div>',
    ]);
  }
}