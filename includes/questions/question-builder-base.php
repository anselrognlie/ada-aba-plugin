<?php

namespace Ada_Aba\Includes\Questions;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Parsedown;

use function PHPUnit\Framework\isEmpty;

abstract class Question_Builder_Base
{
  abstract public function build($model);
  abstract public function get_display_name();
  abstract public function get_slug();
  abstract protected function editorDerived($question);
  abstract protected function previewDerived($request);
  abstract protected function getData($request);

  public function editor($question = null)
  {
    $builder_slug = $this->get_slug();

    $prompt = !empty($question) ? $question->getPrompt() : '';
    $description = !empty($question) ? $question->getDescription() : '';

    $derived_editor = $this->editorDerived($question);

    return $this->get_editor_fragment($builder_slug, $prompt, $description, $derived_editor);
  }

  private function get_editor_fragment($builder_slug, $prompt, $description, $derived_editor)
  {
    ob_start();
    include __DIR__ . '/../partials/question-base-editor-fragment.php';
    return ob_get_clean();
  }

  public function preview($request)
  {
    $parsedown = new Parsedown();
    $prompt = $parsedown->text($request['prompt']);
    $description = $parsedown->text($request['description']);
    $question_html = $this->previewDerived($request);

    return $this->get_base_preview_fragment($prompt, $description, $question_html);
  }

  private function get_base_preview_fragment($prompt, $description, $question_html)
  {
    ob_start();
    include __DIR__ . '/../partials/question-base-preview-fragment.php';
    return ob_get_clean();
  }

  public function save($request)
  {
    $id = $request['id'];
    $slug = $request['slug'];
    $prompt = $request['prompt'];
    $description = $request['description'];
    $data = $this->getData($request);

    Core::log(sprintf('id: %s, slug: %s, prompt: %s, description: %s, data: %s', $id, $slug, $prompt, $description, json_encode($data)));

    $model = null;
    if (!empty($id)) {
      $model = Question::get_by_id($id);
      $model->setSlug($slug);
      $model->setPrompt($prompt);
      $model->setDescription($description);
      $model->setData($data);
      $model->update();
    } else {
      $model = Question::create(get_class($this), $prompt, $description, $data);
      if (!empty($slug)) {
        $model->setSlug($slug);
      }
      $model->insert();
    }

    return [$model->getId(), $model->getSlug()];
  }
}
