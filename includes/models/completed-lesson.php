<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Completed_Lesson
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $learner_id;
  private $lesson_id;
  private $slug;
  private $completed_at;

  public static $table_name = 'ada_aba_completed_lesson';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $learner_id,
    $lesson_id,
    $slug,
    $completed_at,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->learner_id = $learner_id;
    $this->lesson_id = $lesson_id;
    $this->slug = $slug;
    $this->completed_at = $completed_at;
  }

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

  public function getLearnerId()
  {
    return $this->learner_id;
  }

  public function getLessonId()
  {
    return $this->lesson_id;
  }

  public function getSlug()
  {
    return $this->slug;
  }

  public function getCompletedAt()
  {
    return $this->completed_at;
  }

  public function isComplete()
  {
    return !empty($this->completed_at);
  }

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

  public function setLearnerId($learner_id)
  {
    $this->learner_id = $learner_id;
  }

  public function setLessonId($lesson_id)
  {
    $this->lesson_id = $lesson_id;
  }

  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  public function setCompletedAt($completed_at)
  {
    $this->completed_at = $completed_at;
  }

  public static function fromRow($row)
  {
    return new Completed_Lesson(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['learner_id'],
      $row['lesson_id'],
      $row['slug'],
      $row['completed_at'],
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

  // create a new Completed_Lesson from values, excluding those that can be generated
  public static function create(
    $learner_id,
    $lesson_id,
  ) {
    $slug = Core::generate_nonce();
    $db_now = dt_to_sql(new \DateTime());


    return new Completed_Lesson(
      null,
      $db_now,
      $db_now,
      null,
      $learner_id,
      $lesson_id,
      $slug,
      $db_now,
    );
  }

  public function insert()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $data = array(
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'learner_id' => $this->learner_id,
      'lesson_id' => $this->lesson_id,
      'slug' => $this->slug,
      'completed_at' => $this->completed_at,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert Completed Lesson');
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
      'learner_id' => $this->learner_id,
      'lesson_id' => $this->lesson_id,
      'slug' => $this->slug,
      'completed_at' => $this->completed_at,
    );

    $where = array('id' => $this->id);

    $result = $wpdb->update($table_name, $data, $where);
    if ($result === false) {
      throw new Aba_Exception('Failed to update Completed Lesson');
    }
  }

  public static function get_by_learner_id($learner_id)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE learner_id = %d",
        $learner_id
      ),
      'ARRAY_A'
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to retrieve Completed Lessons');
    }

    return array_map(function ($row) {
      return self::fromRow($row);
    }, $result);
  }

  public static function get_by_learner_and_lesson_id($learner_id, $lesson_id)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $row = $wpdb->get_row(
        $wpdb->prepare(
        "SELECT * FROM $table_name WHERE learner_id = %d and lesson_id = %d",
        $learner_id,
        $lesson_id,
      ),
      'ARRAY_A'
    );

    if ($row) {
      return self::fromRow($row);
    } else {
      return null;
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
      throw new Aba_Exception('Failed to delete Completed_Lesson');
    }
  }
}
