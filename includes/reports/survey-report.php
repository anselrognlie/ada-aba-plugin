<?php

namespace Ada_Aba\Includes\Reports;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Services\Survey_Question_Edit_Service;
use Ada_Aba\Includes\Services\Survey_Response_Service;
use Ada_Aba\Parsedown;

class Survey_Report extends Report_Base
{
  private $survey_slug;

  public function __construct($plugin_name, $survey_slug)
  {
    parent::__construct($plugin_name);
    $this->survey_slug = $survey_slug;
  }

  function get_content_type()
  {
    return 'text/csv';
  }

  function get_filename()
  {
    $now = date('Ymd\THis');
    return "survey-report-$now.csv";
  }

  function get_content()
  {
    $survey_slug = $this->survey_slug;
    $sqe_service = new Survey_Question_Edit_Service($this->plugin_name);
    $survey_question_relations = $sqe_service->get_survey_questions($survey_slug);
    $survey_questions_with_responses = array_filter($survey_question_relations, function ($relation) {
      $question = $relation->getQuestion();
      $builder_name = $question->getBuilder();
      $builder = new $builder_name();
      return $builder->gets_response();
    });

    $header = $this->generate_header($survey_questions_with_responses);
    $header_str = Core::csv_str($header);

    $sr_service = new Survey_Response_Service();
    $responses = $sr_service->get_responses($survey_slug);
    // Core::log(print_r($responses, true));

    $lines = [$header_str];
    foreach ($responses as $response) {
      $line = $this->generate_line($survey_questions_with_responses, $response);
      $lines[] = Core::csv_str($line);
    }

    return join(PHP_EOL, $lines);
  }

  private function generate_header($survey_question_relations)
  {
    $header = ['Timestamp'];
    foreach ($survey_question_relations as $survey_question) {
      $question = $survey_question->getQuestion();
      $header[] = $this->generate_question_headers($question);
    }

    return $header;
  }

  private function generate_question_headers($question)
  {
    $parsedown = new Parsedown();
    $prompt = trim(strip_tags($parsedown->text($question->getPrompt())));
    $description = trim(strip_tags($parsedown->text($question->getDescription())));

    $pieces = [$prompt];
    if (!empty($description)) {
      $pieces[] = $description;
    }
    return join(" ", $pieces);
  }

  private function generate_line($survey_question_relations, $response)
  {
    $line = [$response['date']];
    foreach ($survey_question_relations as $survey_question) {
      $question = $survey_question->getQuestion();
      $question_slug = $question->getSlug();
      $line[] = Core::safe_key($response, $question_slug, '');
    }

    return $line;
  }
}
