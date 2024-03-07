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
  private $action_class;
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
    $action_class,
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
    $this->action_class = $action_class;
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

  public function getActionClass()
  {
    return $this->action_class;
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

  public function setActionClass($action_class)
  {
    $this->action_class = $action_class;
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
      $row['action_class'],
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

  public static function clean_expired_challenges()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $wpdb->query(
      $wpdb->prepare(
        "DELETE FROM $table_name WHERE expires_at < %s",
        current_time('mysql', 1)
      )
    );
  }
}
