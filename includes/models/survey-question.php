<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Db_Helpers\Transaction;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Survey_Question
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $survey_id;
  private $question_id;
  private $order;
  private $slug;
  private $optional;

  public static $table_name = 'ada_aba_survey_question';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $survey_id,
    $question_id,
    $order,
    $slug,
    $optional,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->survey_id = $survey_id;
    $this->question_id = $question_id;
    $this->order = $order;
    $this->slug = $slug;
    $this->optional = $optional;
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

  public function getUpdatedAt()
  {
    return $this->updated_at;
  }

  public function getDeletedAt()
  {
    return $this->deleted_at;
  }

  public function getSurveyId()
  {
    return $this->survey_id;
  }

  public function getQuestionId()
  {
    return $this->question_id;
  }

  public function getOrder()
  {
    return $this->order;
  }

  public function getSlug()
  {
    return $this->slug;
  }

  public function isOptional()
  {
    return $this->optional;
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

  public function setUpdatedAt($updated_at)
  {
    $this->updated_at = $updated_at;
  }

  public function setDeletedAt($deleted_at)
  {
    $this->deleted_at = $deleted_at;
  }

  public function setSurveyId($survey_id)
  {
    $this->survey_id = $survey_id;
  }

  public function setQuestionId($question_id)
  {
    $this->question_id = $question_id;
  }

  public function setOrder($order)
  {
    $this->order = $order;
  }

  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  public function setOptional($optional)
  {
    $this->optional = $optional;
  }

  public static function fromRow($row)
  {
    return new Survey_Question(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['survey_id'],
      $row['question_id'],
      $row['order'],
      $row['slug'],
      $row['optional'],
    );
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

  // create a new Survey_Question from values, excluding those that can be generated
  public static function create(
    $survey_id,
    $question_id,
    $order = -1,
    $optional = false,
  ) {
    $nonce = Core::generate_nonce();
    $now = new \DateTime();

    return new Survey_Question(
      null,
      dt_to_sql($now),
      dt_to_sql($now),
      null,
      $survey_id,
      $question_id,
      $order,
      $nonce,
      $optional,
    );
  }

  public function insert()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $order = $this->order;
    $survey_id = $this->survey_id;

    Transaction::start();

    if ($order === -1) {
      $survey_questions = self::get_by_survey_id($survey_id);
      $max_order = array_reduce($survey_questions, function ($max, $survey_question) {
        return max($max, $survey_question->getOrder());
      }, -1);
      $order = $max_order + 1;
    }

    $data = array(
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'survey_id' => $this->survey_id,
      'question_id' => $this->question_id,
      'order' => $order,
      'slug' => $this->slug,
      'optional' => $this->optional,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      Transaction::rollback();
      throw new Aba_Exception('Failed to insert Survey Question');
    } else {
      Transaction::complete();
      $this->id = $wpdb->insert_id;
      $this->order = $order;
    }
  }

  public function update()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $now = new \DateTime();
    $now = dt_to_sql($now);

    $data = array(
      'updated_at' => $now,
      'survey_id' => $this->survey_id,
      'question_id' => $this->question_id,
      'order' => $this->order,
      'slug' => $this->slug,
      'optional' => $this->optional,
    );

    $where = array('id' => $this->id);

    $result = $wpdb->update($table_name, $data, $where);
    if ($result === false) {
      throw new Aba_Exception('Failed to update Survey Question');
    }
  }

  public static function get_by_survey_id($survey_id)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE survey_id = %d ORDER BY `order`",
        $survey_id
      ),
      'ARRAY_A'
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to retrieve Survey Questions');
    }

    if ($result) {
      return array_map(function ($row) {
        return self::fromRow($row);
      }, $result);
    } else {
      return [];
    }
  }

  public static function get_by_question_id($question_id)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE question_id = %d",
        $question_id
      ),
      'ARRAY_A'
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to retrieve Survey Questions');
    }

    if ($result) {
      return array_map(function ($row) {
        return self::fromRow($row);
      }, $result);
    } else {
      return [];
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
      throw new Aba_Exception('Failed to delete Survey Question');
    }
  }

  public function remove()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    Transaction::start();

    $result = $wpdb->delete(
      $table_name,
      array('id' => $this->id)
    );

    if ($result === false) {
      Transaction::rollback();
      throw new Aba_Exception('Failed to delete Survey Question');
    }

    $survey_questions = self::get_by_survey_id($this->survey_id);
    $idx = 0;
    try {
      foreach ($survey_questions as $survey_question) {
        if ($survey_question->getId() !== $this->id) {
          $survey_question->setOrder($idx);
          $idx += 1;
          $survey_question->update();
        }
      }
      Transaction::complete();
    } catch (Aba_Exception $e) {
      Transaction::rollback();
      throw $e;
    }
  }

  static public function swap_order($slug1, $slug2)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;
    $now = dt_to_sql(new \DateTime());

    Transaction::start();
    $survey_question1 = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE slug = %s",
        $slug1
      ),
    );
    $survey_question2 = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE slug = %s",
        $slug2
      ),
    );

    if (!$survey_question1 || !$survey_question2) {
      Transaction::rollback();
      throw new Aba_Exception('Failed to swap Survey Question records');
    }

    $swap1 = $wpdb->update(
      $table_name,
      array('order' => $survey_question2->order, 'updated_at' => $now),
      array('slug' => $slug1)
    );
    $swap2 = $wpdb->update(
      $table_name,
      array('order' => $survey_question1->order, 'updated_at' => $now),
      array('slug' => $slug2)
    );

    if ($swap1 === false || $swap2 === false) {
      Transaction::rollback();
      throw new Aba_Exception('Failed to swap Survey Question records');
    } else {
      Transaction::complete();
    }

    $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE slug = %s",
        $slug1
      ),
      'ARRAY_A'
    );

    if ($row) {
      return self::fromRow($row);
    } else {
      return null;
    }
  }

  public function toggle_optional()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;
    $now = dt_to_sql(new \DateTime());

    $result = $wpdb->update(
      $table_name,
      array('optional' => !$this->optional, 'updated_at' => $now),
      array('id' => $this->id)
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to toggle Survey Question optional');
    }

    $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d",
        $this->id
      ),
      'ARRAY_A'
    );

    if ($row) {
      return self::fromRow($row);
    } else {
      return null;
    }
  }
}
