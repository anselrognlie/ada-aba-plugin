<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Db_Helpers\Transaction;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Survey_Question_Response
{
  private $id;
  private $survey_response_id;
  private $question_id;
  private $response;

  public static $table_name = 'ada_aba_survey_question_response';

  public function __construct(
    $id,
    $survey_response_id,
    $question_id,
    $response,
  ) {
    $this->id = $id;
    $this->survey_response_id = $survey_response_id;
    $this->question_id = $question_id;
    $this->response = $response;
  }

  // Getters
  public function getId()
  {
    return $this->id;
  }

  public function getSurveyResponseId()
  {
    return $this->survey_response_id;
  }

  public function getQuestionId()
  {
    return $this->question_id;
  }

  public function getResponse()
  {
    return $this->response;
  }

  // Setters
  public function setId($id)
  {
    $this->id = $id;
  }

  public function setSurveyResponseId($survey_response_id)
  {
    $this->survey_response_id = $survey_response_id;
  }

  public function setQuestionId($question_id)
  {
    $this->question_id = $question_id;
  }

  public function setResponse($response)
  {
    $this->response = $response;
  }

  public static function fromRow($row)
  {
    return new Survey_Question_Response(
      $row['id'],
      $row['survey_response_id'],
      $row['question_id'],
      $row['response'],
    );
  }

  public static function get_by_id($id)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d",
        $id
      ),
      'ARRAY_A'
    );

    if ($row) {
      return self::fromRow($row);
    } else {
      return null;
    }
  }

  // create a new Survey_Question_Response from values, excluding those that can be generated
  public static function create(
    $survey_response_id,
    $question_id,
    $response,
  ) {
    return new Survey_Question_Response(
      null,
      $survey_response_id,
      $question_id,
      $response,
    );
  }

  public function insert()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $data = array(
      'survey_response_id' => $this->survey_response_id,
      'question_id' => $this->question_id,
      'response' => $this->response,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert Survey_Question_Response');
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  public function delete()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->delete(
      $table_name,
      array('id' => $this->id)
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to delete Survey_Question_Response');
    }
  }
}
