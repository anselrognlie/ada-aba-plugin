<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Learner
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $first_name;
  private $last_name;
  private $email;
  private $slug;

  public static $table_name = 'ada_aba_learner';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $first_name,
    $last_name,
    $email,
    $slug,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email = $email;
    $this->slug = $slug;
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

  public function getFirstName()
  {
    return $this->first_name;
  }

  public function getLastName()
  {
    return $this->last_name;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getSlug()
  {
    return $this->slug;
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

  public function setFirstName($first_name)
  {
    $this->first_name = $first_name;
  }

  public function setLastName($last_name)
  {
    $this->last_name = $last_name;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  public static function get_by_email($email)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE email = %s",
        $email
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

  public static function fromRow($row)
  {
    return new Learner(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['first_name'],
      $row['last_name'],
      $row['email'],
      $row['slug'],
    );
  }

  public static function generateSlug()
  {
    return Core::generate_nonce();
  }

  // create a new learner from values, excluding those that can be generated
  public static function create(
    $first_name,
    $last_name,
    $email
  ) {
    $slug = self::generateSlug();
    $now = new \DateTime();

    return new Learner(
      null,
      dt_to_sql($now),
      dt_to_sql($now),
      null,
      $first_name,
      $last_name,
      $email,
      $slug,
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
      'first_name' => $this->first_name,
      'last_name' => $this->last_name,
      'email' => $this->email,
      'slug' => $this->slug,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert learner');
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  // function to update the managed fields of a learner
  public function update()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $now = new \DateTime();
    $now = dt_to_sql($now);

    $data = array(
      'updated_at' => $now,
      'first_name' => $this->first_name,
      'last_name' => $this->last_name,
      'email' => $this->email,
      'slug' => $this->slug,
    );

    $where = array('id' => $this->id);

    $result = $wpdb->update($table_name, $data, $where);
    if ($result === false) {
      throw new Aba_Exception('Failed to update learner');
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
      throw new Aba_Exception('Failed to delete Learner');
    }
  }
}
