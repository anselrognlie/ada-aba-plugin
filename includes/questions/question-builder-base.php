<?php

namespace Ada_Aba\Includes\Questions;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Parsedown;

abstract class Question_Builder_Base
{
  abstract public function build($model);
  abstract public function create();
  abstract public function get_display_name();
  abstract public function get_slug();
  abstract protected function editorDerived();
  abstract protected function previewDerived($request);
  abstract protected function getData($request);

  public function editor()
  {
    $slug = $this->get_slug();

    return join('', array(
      '<div class="ada-aba-question-editor">',
      '<input type="hidden" id="ada-aba-question-editor-panel-builder-slug" name="builder" value="' . $slug . '" />',
      '<div class="ada-aba-question-editor__prompt">',
      '<label for="ada-aba-question-editor-panel-prompt">Prompt</label>',
      '<textarea id="ada-aba-question-editor-panel-prompt" name="prompt" rows="4" cols="50"></textarea>',
      '</div>',
      '<div class="ada-aba-question-editor__description">',
      '<label for="ada-aba-question-editor-panel-description">Description</label>',
      '<textarea id="ada-aba-question-editor-panel-description" name="description" rows="4" cols="50"></textarea>',
      '</div>',
      $this->editorDerived(),
      '</div>',
    ));
  }

  public function preview($request)
  {
    $parsedown = new Parsedown();
    $prompt = $request['prompt'];
    $description = $request['description'];
    return join('', array(
      '<div class="ada-aba-question-preview">',
      $parsedown->text($prompt),
      $parsedown->text($description),
      $this->previewDerived($request),
      '</div>',
    ));
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
      $model = Question::create(get_class($this), $slug, $prompt, $description, $data);
      $model->insert();
    }

    return [$model->getId(), $model->getSlug()];
  }
}
