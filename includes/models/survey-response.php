<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Db_Helpers\Transaction;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Survey_Response
{
  private $id;
  private $created_at;  // restrict to day granularity to reduce uniqueness
  private $slug;
  private $survey_id;

  public static $table_name = 'ada_aba_survey_response';

  public function __construct(
    $id,
    $created_at,
    $slug,
    $survey_id,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->slug = $slug;
    $this->survey_id = $survey_id;
  }

  // Getters
  public function getId()
  {
    return $this->id;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  public function getSlug()
  {
    return $this->slug;
  }

  public function getSurveyId()
  {
    return $this->survey_id;
  }

  // Setters
  public function setId($id)
  {
    $this->id = $id;
  }

  public function setCreatedAt($created_at)
  {
    $this->created_at = $created_at;
  }

  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  public function setSurveyId($survey_id)
  {
    $this->survey_id = $survey_id;
  }

  public static function fromRow($row)
  {
    return new Survey_Response(
      $row['id'],
      $row['created_at'],
      $row['slug'],
      $row['survey_id'],
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

  public static function get_by_slug($slug)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE slug = %s",
        $slug
      ),
      'ARRAY_A'
    );

    if ($row) {
      return self::fromRow($row);
    } else {
      return null;
    }
  }

  // create a new Survey_Response from values, excluding those that can be generated
  public static function create(
    $survey_id,
  ) {
    $nonce = Core::generate_nonce();
    $now = new \DateTime();
    $now->setTime(0, 0);  // restrict to day granularity to reduce uniqueness

    return new Survey_Response(
      null,
      dt_to_sql($now),
      $nonce,
      $survey_id,
    );
  }

  public function insert()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $data = array(
      'created_at' => $this->created_at,
      'slug' => $this->slug,
      'survey_id' => $this->survey_id,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert Survey_Response');
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
      throw new Aba_Exception('Failed to delete Survey_Response');
    }
  }
}
