<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Question
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $slug;
  private $builder;
  private $prompt;
  private $description;
  private $data;

  public static $table_name = 'ada_aba_question';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $slug,
    $builder,
    $prompt,
    $description,
    $data
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->slug = $slug;
    $this->builder = $builder;
    $this->prompt = $prompt;
    $this->description = $description;
    $this->data = $data;
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

  public function getSlug()
  {
    return $this->slug;
  }

  public function getBuilder()
  {
    return $this->builder;
  }

  public function getPrompt()
  {
    return $this->prompt;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function getData()
  {
    return $this->data;
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

  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  public function setBuilder($builder)
  {
    $this->builder = $builder;
  }

  public function setPrompt($prompt)
  {
    $this->prompt = $prompt;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function setData($data)
  {
    $this->data = $data;
  }

  public static function fromRow($row)
  {
    return new Question(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['slug'],
      $row['builder'],
      $row['prompt'],
      $row['description'],
      $row['data']
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

    $ids_str = join(',', $ids);

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

  // create a new Question from values, excluding those that can be generated
  public static function create(
    $builder,
    $prompt,
    $description,
    $data,
  ) {
    $nonce = Core::generate_nonce();
    $now = new \DateTime();

    return new Question(
      null,
      dt_to_sql($now),
      dt_to_sql($now),
      null,
      $nonce,
      $builder,
      $prompt,
      $description,
      $data,
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
      'slug' => $this->slug,
      'builder' => $this->builder,
      'prompt' => $this->prompt,
      'description' => $this->description,
      'data' => $this->data,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert Question');
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
      'slug' => $this->slug,
      'builder' => $this->builder,
      'prompt' => $this->prompt,
      'description' => $this->description,
      'data' => $this->data,
    );

    $result = $wpdb->update(
      $table_name,
      $data,
      array('id' => $this->id)
    );

    if ($result === false) {
      throw new Aba_Exception('Failed to update Question');
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
      throw new Aba_Exception('Failed to delete Question');
    }
  }
}