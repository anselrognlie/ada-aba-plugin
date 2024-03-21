<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Models\Db_Helpers\Transaction;

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

  // create a new Syllabus from values, excluding those that can be generated
  public static function create(
    $course_id,
    $lesson_id,
    $order = -1,
    $optional = false,
  ) {
    $nonce = Core::generate_nonce();
    $now = new \DateTime();

    if ($order === -1) {
      $syllabuses = self::get_by_course_id($course_id);
      $max_order = array_reduce($syllabuses, function ($max, $syllabus) {
        return max($max, $syllabus->getOrder());
      }, -1);
      $order = $max_order + 1;
    }

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

  public static function create_by_slug(
    $course_slug,
    $lesson_slug,
    $order = -1,
    $optional = false,
  ) {
    $course = Course::get_by_slug($course_slug);
    $lesson = Lesson::get_by_slug($lesson_slug);

    if (!$course) {
      throw new Aba_Exception('Course not found');
    }

    if (!$lesson) {
      throw new Aba_Exception('Lesson not found');
    }

    return self::create(
      $course->getId(),
      $lesson->getId(),
      $order,
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

  public function update()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $now = new \DateTime();
    $now = dt_to_sql($now);

    $data = array(
      'updated_at' => $now,
      'course_id' => $this->course_id,
      'lesson_id' => $this->lesson_id,
      'order' => $this->order,
      'slug' => $this->slug,
      'optional' => $this->optional,
    );

    $where = array('id' => $this->id);

    $result = $wpdb->update($table_name, $data, $where);
    if ($result === false) {
      throw new Aba_Exception('Failed to update learner');
    }
  }

  public static function get_by_course_slug($course_slug)
  {
    global $wpdb;

    $course_table_name = $wpdb->prefix . Course::$table_name;
    $syllabus_table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT s.* FROM $syllabus_table_name s
          JOIN $course_table_name c ON s.course_id = c.id
          WHERE c.slug = %s
          ORDER BY s.order",
        $course_slug
      ),
      'ARRAY_A'
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to retrieve Syllabuses');
    }

    if ($result) {
      return array_map(function ($row) {
        return self::fromRow($row);
      }, $result);
    } else {
      return [];
    }
  }

  public static function get_by_course_id($course_id)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE course_id = %d ORDER BY `order`",
        $course_id
      ),
      'ARRAY_A'
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to retrieve Syllabuses');
    }

    if ($result) {
      return array_map(function ($row) {
        return self::fromRow($row);
      }, $result);
    } else {
      return [];
    }
  }

  public static function get_by_lesson_id($lesson_id)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE lesson_id = %d",
        $lesson_id
      ),
      'ARRAY_A'
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to retrieve Syllabuses');
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
      throw new Aba_Exception('Failed to delete Syllabus');
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
      throw new Aba_Exception('Failed to delete Syllabus');
    }

    $syllabuses = self::get_by_course_id($this->course_id);
    $idx = 0;
    try {
      foreach ($syllabuses as $syllabus) {
        if ($syllabus->getId() !== $this->id) {
          $syllabus->setOrder($idx);
          $idx += 1;
          $syllabus->update();
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
    $syllabus1 = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE slug = %s",
        $slug1
      ),
    );
    $syllabus2 = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE slug = %s",
        $slug2
      ),
    );

    if (!$syllabus1 || !$syllabus2) {
      Transaction::rollback();
      throw new Aba_Exception('Failed to swap Syllabus records');
    }

    $swap1 = $wpdb->update(
      $table_name,
      array('order' => $syllabus2->order, 'updated_at' => $now),
      array('slug' => $slug1)
    );
    $swap2 = $wpdb->update(
      $table_name,
      array('order' => $syllabus1->order, 'updated_at' => $now),
      array('slug' => $slug2)
    );

    if ($swap1 === false || $swap2 === false) {
      Transaction::rollback();
      throw new Aba_Exception('Failed to swap Syllabus records');
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
      throw new Aba_Exception('Failed to toggle Syllabus optional');
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
