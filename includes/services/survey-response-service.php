<?php

namespace Ada_Aba\Includes\Services;

use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Db_Helpers\Transaction;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Models\Survey_Question;
use Ada_Aba\Includes\Models\Survey_Question_Response;
use Ada_Aba\Includes\Models\Survey_Response;
use Ada_Aba\Includes\Models\Surveyed_Learner;

class Survey_Response_Service
{
  public function process_survey_responses($survey_slug, $learner_slug, $data)
  {
    // $data is an associative array of question slugs to responses

    $survey = Survey::get_by_slug($survey_slug);
    if (!$survey) {
      throw new Aba_Exception('Error processing survey');
    }

    $learner = Learner::get_by_slug($learner_slug);
    if (!$learner) {
      throw new Aba_Exception('Error processing survey');
    }

    $sqe_service = new Survey_Question_Edit_Service();
    $survey_question_relations = $sqe_service->get_survey_questions($survey_slug);

    Transaction::start();
    try {
      $survey_response = Survey_Response::create($survey->getId());
      $survey_response->insert();

      foreach ($survey_question_relations as $relation) {
        $question = $relation->getQuestion();
        $builder_name = $question->getBuilder();
        $builder = new $builder_name();
        $question_slug = $question->getSlug();

        // skip questions that produce no response
        if (!$builder->gets_response()) {
          continue;
        }

        if (!$builder->is_response_valid($question_slug, $data)) {
          throw new Aba_Exception("Error processing survey: invalid response for question [$question_slug]");
        }

        $response = $builder->get_response($question_slug, $data);
        if ($response === '') {
          if (!$relation->isOptional()) {
            throw new Aba_Exception("Error processing survey: non-optional question [$question_slug] not answered");
          }

          continue;
        }

        // Core::log($question_slug . ': ' . $response);

        $survey_question_response = Survey_Question_Response::create(
          $survey_response->getId(),
          $question->getId(),
          $response
        );

        $survey_question_response->insert();
      }

      Surveyed_Learner::insert($learner_slug);

      Transaction::complete();
    } catch (\Exception $e) {
      Transaction::rollback();
      throw $e;
    }
  }

  public function render_survey($survey_slug, $form_state, $data)
  {
    $survey = Survey::get_by_slug($survey_slug);
    $survey_name = $survey->getName();

    $error = Core::safe_key($data, 'error', '');

    $sqe_service = new Survey_Question_Edit_Service();
    $survey_question_relations = $sqe_service->get_survey_questions($survey_slug);
    $questions_html = array_map(function ($survey_question_relation) use ($data) {
      $model = $survey_question_relation->getQuestion();
      $builder_class = $model->getBuilder();
      $builder = new $builder_class;
      $question = $builder->build($model);
      $optional = $survey_question_relation->isOptional();
      return $question->render(!$optional, $data);
    }, $survey_question_relations);

    return $this->get_survey_form($survey_name, $questions_html, $error, $form_state);
  }

  public function get_responses($survey_slug)
  {
    $survey = Survey::get_by_slug($survey_slug);
    $survey_id = $survey->getId();

    global $wpdb;
    $survey_table_name = $wpdb->prefix . Survey::$table_name;
    $survey_response_table_name = $wpdb->prefix . Survey_Response::$table_name;
    $question_table_name = $wpdb->prefix . Question::$table_name;
    $survey_question_response_table_name = $wpdb->prefix . Survey_Question_Response::$table_name;

    $query = $wpdb->prepare(
      "SELECT s.slug as survey_slug,"
        . " sr.slug as survey_response_slug,"
        . " sr.created_at as `date`,"
        . " q.slug as question_slug,"
        . " sqr.response"
        . " FROM $survey_table_name s"
        . " JOIN $survey_response_table_name sr ON s.id = sr.survey_id"
        . " JOIN $survey_question_response_table_name sqr ON sqr.survey_response_id = sr.id"
        . " JOIN $question_table_name q ON sqr.question_id = q.id"
        . " WHERE s.slug = %s"
        . " ORDER BY sr.created_at, s.slug",
      $survey_slug
    );

    $result = $wpdb->get_results($query, 'OBJECT');

    if (!$result) {
      return [];
    }
    
    $responses = [];
    foreach ($result as $row) {
      $response_slug = $row->survey_response_slug;
      if (!array_key_exists($response_slug, $responses)) {
        $date = $row->date;
        $responses[$response_slug] = ['date' => $date];
      }
      $response = &$responses[$response_slug];
      $question_slug = $row->question_slug;
      $response[$question_slug] = $row->response;
    }

    return $responses;
  }

  private function get_survey_form($survey_name, $questions_html, $error, $form_state = '')
  {
    ob_start();
    include __DIR__ . '/../partials/survey-form.php';
    return ob_get_clean();
  }
}
