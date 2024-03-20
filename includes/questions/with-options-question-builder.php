<?php

namespace Ada_Aba\Includes\Questions;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Fragments\Multiple_Choice_Option;
use Ada_Aba\Parsedown;

abstract class With_Options_Question_Builder extends Question_Builder_Base
{
  protected abstract function buildDerived($id, $slug, $prompt, $description, $options, $show_other);
  protected abstract function get_control_type();

  public function build($model)
  {
    $id = $model->getId();
    $slug = $model->getSlug();
    $prompt = $model->getPrompt();
    $description = $model->getDescription();
    $data = json_decode($model->getData(), associative: true);
    $options = Core::safe_key($data, 'options', []);
    $show_other = Core::safe_key($data, 'show_other', false);

    return $this->buildDerived(
      $id,
      $slug,
      $prompt,
      $description,
      $options,
      $show_other,
    );
  }

  protected function editorDerived($question)
  {
    $template_content = $this->get_option_fragment('');
    $option_content = (!empty($question)) ? $this->optionsEditor($question) : '';
    $show_other = (!empty($question)) ? $question->getShowOther() : false;

    return $this->get_editor_fragment($template_content, $option_content, $show_other);
  }

  private function get_option_fragment($option)
  {
    ob_start();
    include __DIR__ . '/../partials/with-options-option-fragment.php';
    return ob_get_clean();
  }

  private function optionsEditor($question)
  {
    $options = $question->getOptions();
    $lines = [];
    foreach ($options as $option) {
      $lines[] = $this->get_option_fragment($option);
    }
    return join('', $lines);
  }

  private function get_editor_fragment($template_content, $option_content, $show_other)
  {
    ob_start();
    include __DIR__ . '/../partials/with-options-editor-fragment.php';
    return ob_get_clean();
  }

  protected function previewDerived($request)
  {
    $control_type = $this->get_control_type();
    $options = $request['options'];
    $show_other = Core::safe_key($request, 'show_other', false);

    $options_html = $this->optionsPreview($control_type, $options);
    $other_html = $this->otherPreview($control_type, $show_other);

    return $this->get_options_preview($options_html, $other_html);
  }

  private function optionsPreview($control_type, $options)
  {
    $parsedown = new Parsedown();
    $lines = [];
    foreach ($options as $option) {
      $option_text = $parsedown->text($option);
      $lines[] = $this->get_option_preview($control_type, $option_text);
    }
    return join('', $lines);
  }

  private function get_options_preview($options_html, $other_html)
  {
    ob_start();
    include __DIR__ . '/../partials/with-options-options-preview-fragment.php';
    return ob_get_clean();
  }

  private function get_option_preview($control_type, $option)
  {
    ob_start();
    include __DIR__ . '/../partials/with-options-option-preview-fragment.php';
    return ob_get_clean();
  }

  private function otherPreview($control_type, $show_other)
  {
    if ($show_other) {
      return $this->get_other_preview($control_type);
    }
    return '';
  }

  private function get_other_preview($control_type)
  {
    ob_start();
    include __DIR__ . '/../partials/with-options-other-preview-fragment.php';
    return ob_get_clean();
  }

  protected function getData($request)
  {
    $options = $request['options'];
    $show_other = Core::safe_key($request, 'show_other', false);

    return json_encode([
      'options' => $options,
      'show_other' => $show_other,
    ]);
  }
}
