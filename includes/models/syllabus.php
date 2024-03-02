<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Syllabus
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $course_id;
  private $lesson_id;
  private $order;
  private $slug;
  private $optional;

  public static $table_name = 'ada_aba_syllabus';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $course_id,
    $lesson_id,
    $order,
    $slug,
    $optional,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->course_id = $course_id;
    $this->lesson_id = $lesson_id;
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

  public function getCourseId()
  {
    return $this->course_id;
  }

  public function getLessonId()
  {
    return $this->lesson_id;
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

  public function setCourseId($course_id)
  {
    $this->course_id = $course_id;
  }

  public function setLessonId($lesson_id)
  {
    $this->lesson_id = $lesson_id;
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
    // Core::log(print_r($row, true));
    return new Syllabus(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['course_id'],
      $row['lesson_id'],
      $row['order'],
      $row['slug'],
      $row['optional'],
    );
  }

  // create a new Syllabus from values, excluding those that can be generated
  public static function create(
    $course_id,
    $lesson_id,
    $order,
    $optional = false,
  ) {
    $nonce = Core::generate_nonce();
    $now = new \DateTime();

    return new Syllabus(
      null,
      dt_to_sql($now),
      dt_to_sql($now),
      null,
      $course_id,
      $lesson_id,
      $order,
      $nonce,
      $optional,
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
      'course_id' => $this->course_id,
      'lesson_id' => $this->lesson_id,
      'order' => $this->order,
      'slug' => $this->slug,
      'optional' => $this->optional,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert Syllabus');
    }
  }
}
