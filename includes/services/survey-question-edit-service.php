<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Includes\Models\Survey_Question;
use Ada_Aba\Includes\Relations\Survey_Question_Relations;

class Survey_Question_Edit_Service {
  public function get_survey_questions($survey_slug)
  {
    $survey = Survey::get_by_slug($survey_slug);
    $sq_service = new Survey_Question_Service();
    $survey_questions = $sq_service->get_by_survey_slug($survey_slug);
    $question_ids = array_map(function($survey_question) {
      return $survey_question->getQuestionId();
    }, $survey_questions);
    $questions = Question::get_by_ids($question_ids);
    $questions_idx = array_reduce($questions, function($acc, $question) {
      $acc[$question->getId()] = $question;
      return $acc;
    }, []);

    $survey_question_relations = array_map(function($survey_question) use ($survey, $questions_idx) {
      $question = $questions_idx[$survey_question->getQuestionId()];
      return new Survey_Question_Relations(
        $survey_question,
        $survey,
        $question,
      );
    }, $survey_questions);

    return $survey_question_relations;
  }

  public function get_available_questions($relative_to_survey_slug)
  {
    $questions = Question::all();
    $survey_question_relations = $this->get_survey_questions($relative_to_survey_slug);
    $survey_question_ids = array_fill_keys(array_map(function($survey_question_relation) {
      return $survey_question_relation->getQuestion()->getId();
    }, $survey_question_relations), true);
    $filtered_questions = array_filter($questions, function($question) use ($survey_question_ids) {
      return !isset($survey_question_ids[$question->getId()]);
    });
    return $filtered_questions;
  }

  public function move_up($survey_question_slug)
  {
    $s_service = new Survey_Service();
    $survey = $s_service->get_by_survey_question_slug($survey_question_slug);
    $survey_question_relations = $this->get_survey_questions($survey->getSlug());
    
    // locate the survey_question immediately above the specified survey_question
    $idx = -1;
    foreach ($survey_question_relations as $survey_question_relation) {
      if ($survey_question_relation->getSlug() === $survey_question_slug) {
        break;
      }
      $idx += 1;
    }
    
    if ($idx === -1) {
      // already at top
      return;
    }
    
    $higher_slug = $survey_question_relations[$idx]->getSlug();
    Survey_Question::swap_order($survey_question_slug, $higher_slug);
    
    return Survey_Question::get_by_slug($survey_question_slug);
  }

  public function move_down($survey_question_slug)
  {
    $s_service = new Survey_Service();
    $survey = $s_service->get_by_survey_question_slug($survey_question_slug);
    $survey_question_relations = $this->get_survey_questions($survey->getSlug());

    // locate the survey_question immediately above the specified survey_question
    $idx = 1;
    foreach ($survey_question_relations as $survey_question_relation) {
      if ($survey_question_relation->getSlug() === $survey_question_slug) {
        break;
      }
      $idx += 1;
    }

    if ($idx >= count($survey_question_relations)) {
      // already at bottom
      return;
    }

    $lower_slug = $survey_question_relations[$idx]->getSlug();
    Survey_Question::swap_order($survey_question_slug, $lower_slug);

    return Survey_Question::get_by_slug($survey_question_slug);
  }
}
