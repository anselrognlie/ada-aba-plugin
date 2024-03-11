<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Challenge_Action
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $slug;
  private $expires_at;
  private $action_builder;
  private $action_payload;
  private $nonce;
  private $email;

  public static $table_name = 'ada_aba_challenge_action';

  public function __construct(
    $id,
    $created_at,
    $updated_at,
    $deleted_at,
    $slug,
    $email,
    $nonce,
    $expires_at,
    $action_builder,
    $action_payload,
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->nonce = $nonce;
    $this->slug = $slug;
    $this->email = $email;
    $this->expires_at = $expires_at;
    $this->action_builder = $action_builder;
    $this->action_payload = $action_payload;
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

  public function getExpiresAt()
  {
    return $this->expires_at;
  }

  public function getActionBuilder()
  {
    return $this->action_builder;
  }

  public function getActionPayload()
  {
    return $this->action_payload;
  }

  public function getNonce()
  {
    return $this->nonce;
  }

  public function getEmail()
  {
    return $this->email;
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

  public function setExpiresAt($expires_at)
  {
    $this->expires_at = $expires_at;
  }

  public function setActionBuilder($action_builder)
  {
    $this->action_builder = $action_builder;
  }

  public function setActionPayload($action_payload)
  {
    $this->action_payload = $action_payload;
  }

  public function setNonce($nonce)
  {
    $this->nonce = $nonce;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public static function fromRow($row)
  {
    return new self(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['slug'],
      $row['email'],
      $row['nonce'],
      $row['expires_at'],
      $row['action_builder'],
      $row['action_payload'],
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
      ARRAY_A
    );

    if (!$row) {
      return null;
    }

    return self::fromRow($row);
  }

  public static function get_by_nonce($nonce)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE nonce = %s",
        $nonce
      ),
      ARRAY_A
    );

    if (!$row) {
      return null;
    }

    return self::fromRow($row);
  }

  public static function get_expired_challenges()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $rows = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT * FROM $table_name WHERE expires_at < %s",
        current_time('mysql', 1)
      ),
      ARRAY_A
    );

    if (!$rows) {
      return [];
    }

    return array_map(function ($row) {
      return self::fromRow($row);
    }, $rows);
  }

  public static function generateNonce()
  {
    $nonce = Core::generate_nonce();
    $expires_at = new \DateTime();
    $expires_at->add(new \DateInterval('PT30M'));
    return [$nonce, $expires_at];
  }

  // create a new learner from values, excluding those that can be generated
  public static function create(
    $email,
    $action_builder,
    $action_payload,
  ) {
    $slug = Core::generate_nonce();
    [$challenge_nonce, $challenge_expires_at] = self::generateNonce();
    $now = new \DateTime();

    return new Challenge_Action(
      null,
      dt_to_sql($now),
      dt_to_sql($now),
      null,
      $slug,
      $email,
      $challenge_nonce,
      dt_to_sql($challenge_expires_at),
      $action_builder,
      $action_payload,
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
      'slug' => $this->slug,
      'email' => $this->email,
      'nonce' => $this->nonce,
      'expires_at' => $this->expires_at,
      'action_builder' => $this->action_builder,
      'action_payload' => $this->action_payload,
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Aba_Exception('Failed to insert challenge action');
    } else {
      $this->id = $wpdb->insert_id;
    }
  }

  public function delete()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $wpdb->delete(
      $table_name,
      array('id' => $this->id),
      array('%d')
    );
  }
}
