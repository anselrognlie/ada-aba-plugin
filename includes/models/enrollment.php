<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Enrollment
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $learner_id;
  private $course_id;
  private $slug;
  private $started_at;
  private $completed_at;
  private $completion;

  public static $table_name = 'ada_aba_enrollment';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $learner_id,
    $course_id,
    $slug,
    $started_at,
    $completed_at,
    $completion,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->learner_id = $learner_id;
    $this->course_id = $course_id;
    $this->slug = $slug;
    $this->started_at = $started_at;
    $this->completed_at = $completed_at;
    $this->completion = $completion;
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

  public function getCourseId()
  {
    return $this->course_id;
  }

  public function getSlug()
  {
    return $this->slug;
  }

  public function getStartedAt()
  {
    return $this->started_at;
  }

  public function getCompletedAt()
  {
    return $this->completed_at;
  }

  public function getCompletion()
  {
    return $this->completion;
  }

  public function isComplete()
  {
    return !empty($this->completion);
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

  public function setCourseId($course_id)
  {
    $this->course_id = $course_id;
  }

  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  public function setStartedAt($started_at)
  {
    $this->started_at = $started_at;
  }

  public function setCompletedAt($completed_at)
  {
    $this->completed_at = $completed_at;
  }

  public function setCompletion($completion)
  {
    $this->completion = $completion;
  }

  public static function fromRow($row)
  {
    return new Enrollment(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['learner_id'],
      $row['course_id'],
      $row['slug'],
      $row['started_at'],
      $row['completed_at'],
      $row['completion'],
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

  // create a new Enrollment from values, excluding those that can be generated
  public static function create(
    $learner_id,
    $course_id,
  ) {
    $slug = Core::generate_nonce();
    $db_now = dt_to_sql(new \DateTime());


    return new Enrollment(
      null,
      $db_now,
      $db_now,
      null,
      $learner_id,
      $course_id,
      $slug,
      $db_now,
      null,
      null,
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
      'course_id' => $this->course_id,
      'slug' => $this->slug,
      'started_at' => $this->started_at,
      'completed_at' => $this->completed_at,
      'completion' => $this->completion,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert Enrollment');
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
      'course_id' => $this->course_id,
      'slug' => $this->slug,
      'started_at' => $this->started_at,
      'completed_at' => $this->completed_at,
      'completion' => $this->completion,
    );

    $where = array('id' => $this->id);

    $result = $wpdb->update($table_name, $data, $where);
    if ($result === false) {
      throw new Aba_Exception('Failed to update learner');
    }
  }

  public static function get_by_learner_id(
    $learner_id,
    $by_priority = false
  ) {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $order = '';
    if ($by_priority) {
      $order = 'ORDER BY started_at DESC';
    }

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE learner_id = %d $order",
        $learner_id
      ),
      'ARRAY_A'
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to retrieve Enrollments');
    }

    return array_map(function ($row) {
      return self::fromRow($row);
    }, $result);
  }

  public static function get_by_learner_course(
    $learner_id,
    $course_id
  ) {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE learner_id = %d AND course_id = %d",
        $learner_id,
        $course_id,
      ),
      'ARRAY_A'
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to retrieve Enrollments');
    }

    if ($result) {
      return self::fromRow($result);
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
      throw new Aba_Exception('Failed to delete Enrollment');
    }
  }

  public function complete()
  {
    $this->completed_at = dt_to_sql(new \DateTime());
    $this->completion = Core::generate_nonce();
    $this->update();
  }
}
