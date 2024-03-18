<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Lesson
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $name;
  private $slug;
  private $url;
  private $complete_on_progress;

  public static $table_name = 'ada_aba_lesson';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $name,
    $slug,
    $url,
    $complete_on_progress,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->name = $name;
    $this->slug = $slug;
    $this->url = $url;
    $this->complete_on_progress = $complete_on_progress;
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

  public function getName()
  {
    return $this->name;
  }

  public function getSlug()
  {
    return $this->slug;
  }

  public function getUrl()
  {
    return $this->url;
  }

  public function canCompleteOnProgress()
  {
    return $this->complete_on_progress;
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

  public function setName($name)
  {
    $this->name = $name;
  }

  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  public function setUrl($url)
  {
    $this->url = $url;
  }

  public function setCompleteOnProgress($complete_on_progress)
  {
    $this->complete_on_progress = $complete_on_progress;
  }

  public static function fromRow($row)
  {
    return new Lesson(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['name'],
      $row['slug'],
      $row['url'],
      $row['complete_on_progress'],
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

  public static function get_by_ids($ids)
  {
    global $wpdb;

    if (count($ids) === 0) {
      return [];
    }

    $table_name = $wpdb->prefix . self::$table_name;

    $ids_str = implode(',', $ids);

    $result = $wpdb->get_results(
      "SELECT * FROM $table_name WHERE id IN ($ids_str)",
      'ARRAY_A'
    );

    if ($result) {
      return array_map(function ($row) {
        return self::fromRow($row);
      }, $result);
    } else {
      return [];
    }
  }

  // create a new Lesson from values, excluding those that can be generated
  public static function create(
    $name,
    $url,
    $complete_on_progress,
  ) {
    $nonce = Core::generate_nonce();
    $now = new \DateTime();

    return new Lesson(
      null,
      dt_to_sql($now),
      dt_to_sql($now),
      null,
      $name,
      $nonce,
      $url,
      $complete_on_progress,
    );
  }

  public static function all()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $result = $wpdb->get_results(
      "SELECT * FROM $table_name",
      'ARRAY_A'
    );

    if ($result) {
      return array_map(function ($row) {
        return self::fromRow($row);
      }, $result);
    } else {
      return [];
    }
  }

  public function insert()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $data = array(
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'deleted_at' => $this->deleted_at,
      'name' => $this->name,
      'slug' => $this->slug,
      'url' => $this->url,
      'complete_on_progress' => $this->complete_on_progress,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert Lesson');
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  public function update()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $data = array(
      'updated_at' => dt_to_sql(new \DateTime()),
      'name' => $this->name,
      'slug' => $this->slug,
      'url' => $this->url,
      'complete_on_progress' => $this->complete_on_progress,
    );

    $result = $wpdb->update(
      $table_name,
      $data,
      array('id' => $this->id)
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to update Lesson');
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
      throw new Aba_Exception('Failed to delete Lesson');
    }
  }
}
