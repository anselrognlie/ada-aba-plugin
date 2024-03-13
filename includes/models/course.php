<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Course
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $name;
  private $slug;
  private $active;

  public static $table_name = 'ada_aba_course';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $name,
    $slug,
    $active,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->name = $name;
    $this->slug = $slug;
    $this->active = $active;
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

  public function isActive()
  {
    return $this->active;
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

  public function setActive($active)
  {
    $this->active = $active;
  }

  // public static function get_by_email($email) {
  //   global $wpdb;

  //   $table_name = $wpdb->prefix . self::$table_name;

  //   $row = $wpdb->get_row(
  //     "SELECT * FROM $table_name WHERE email = '$email'",
  //     'ARRAY_A'
  //   );

  //   if ($row) {
  //     return self::fromRow($row);
  //   } else {
  //     return null;
  //   }
  // }

  public static function fromRow($row)
  {
    return new Course(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['name'],
      $row['slug'],
      $row['active'],
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

  public static function get_by_ids($ids)
  {
    if (count($ids) === 0) {
      return [];
    }

    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $ids_str = implode(',', $ids);
    $rows = $wpdb->get_results(
      "SELECT * FROM $table_name WHERE id IN ($ids_str)",
      'ARRAY_A'
    );

    if ($rows) {
      return array_map(function ($row) {
        return self::fromRow($row);
      }, $rows);
    } else {
      return [];
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

  public static function get_active_course()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $row = $wpdb->get_row(
      "SELECT * FROM $table_name WHERE active = 1",
      'ARRAY_A'
    );

    if ($row) {
      return self::fromRow($row);
    } else {
      return null;
    }
  }

  public static function activate($slug)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;
    $now = dt_to_sql(new \DateTime());

    $wpdb->query("START TRANSACTION");
    $deactivate = $wpdb->query(
      "UPDATE $table_name SET active = 0, updated_at = '$now' WHERE active = 1"
    );
    $activate = $wpdb->query(
      $wpdb->prepare(
        "UPDATE $table_name SET active = 1, updated_at = '$now' WHERE slug = %s",
        $slug
      )
    );

    if ($deactivate === false || $activate === false) {
      $wpdb->query("ROLLBACK");
      throw new Aba_Exception('Failed to activate Course');
    } else {
      $wpdb->query("COMMIT");
    }

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

  // create a new Course from values, excluding those that can be generated
  public static function create(
    $name,
    $active = false,
  ) {
    $nonce = Core::generate_nonce();
    $now = new \DateTime();

    return new Course(
      null,
      dt_to_sql($now),
      dt_to_sql($now),
      null,
      $name,
      $nonce,
      $active,
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
      'active' => $this->active,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert Course');
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
      'active' => $this->active,
    );

    $result = $wpdb->update(
      $table_name,
      $data,
      array('id' => $this->id)
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to update Course');
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
      throw new Aba_Exception('Failed to delete Course');
    }
  }
}
