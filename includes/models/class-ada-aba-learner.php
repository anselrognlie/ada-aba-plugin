<?php

namespace Models;

use Ada_Aba;
use Ada_Aba_Exception;

use function Db_Helpers\dt_to_sql;

class Ada_Aba_Learner
{
  private $id;
  private $created_at;
  private $updated_at;
  private $deleted_at;
  private $first_name;
  private $last_name;
  private $email;
  private $slug;
  private $challenge_nonce;
  private $challenge_expires_at;
  private $verified;

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
    $challenge_nonce,
    $challenge_expires_at,
    $verified
  ) {
    $this->id = $id;
    $this->created_at = $created_at;
    $this->updated_at = $updated_at;
    $this->deleted_at = $deleted_at;
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email = $email;
    $this->slug = $slug;
    $this->challenge_nonce = $challenge_nonce;
    $this->challenge_expires_at = $challenge_expires_at;
    $this->verified = $verified;
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

  public function getChallengeNonce()
  {
    return $this->challenge_nonce;
  }

  public function getChallengeExpiresAt()
  {
    return $this->challenge_expires_at;
  }

  public function getVerified()
  {
    return $this->verified;
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

  public function setChallengeNonce($challenge_nonce)
  {
    $this->challenge_nonce = $challenge_nonce;
  }

  public function setChallengeExpiresAt($challenge_expires_at)
  {
    $this->challenge_expires_at = $challenge_expires_at;
  }

  public function setVerified($verified)
  {
    $this->verified = $verified;
  }

  public static function get_by_email($email)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $row = $wpdb->get_row(
      "SELECT * FROM $table_name WHERE email = '$email'",
      'ARRAY_A'
    );

    if ($row) {
      return self::fromRow($row);
    } else {
      return null;
    }
  }

  public static function get_by_verify_code($verify_code, $restrict_verified = true, $verified = 0)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $cmd = "SELECT * FROM $table_name WHERE challenge_nonce = '$verify_code'";

    if ($restrict_verified) {
      $verified_num = $verified ? 1 : 0;
      $cmd .= " AND verified = $verified_num";
    }

    $row = $wpdb->get_row($cmd, 'ARRAY_A');

    if ($row) {
      return self::fromRow($row);
    } else {
      return null;
    }
  }

  public static function fromRow($row)
  {
    // Ada_Aba::log(print_r($row, true));
    return new Ada_Aba_Learner(
      $row['id'],
      $row['created_at'],
      $row['updated_at'],
      $row['deleted_at'],
      $row['first_name'],
      $row['last_name'],
      $row['email'],
      $row['slug'],
      $row['challenge_nonce'],
      $row['challenge_expires_at'],
      $row['verified']
    );
  }

  public static function generateSlug()
  {
    return Ada_Aba::generate_nonce();
  }

  public static function generateNonce()
  {
    $nonce = Ada_Aba::generate_nonce();
    $expires_at = new \DateTime();
    $expires_at->add(new \DateInterval('PT30M'));
    return [$nonce, $expires_at];
  }

  // create a new learner from values, excluding those that can be generated
  public static function create(
    $first_name,
    $last_name,
    $email
  ) {
    $slug = self::generateSlug($first_name, $last_name);
    [$challenge_nonce, $challenge_expires_at] = self::generateNonce();
    $now = new \DateTime();

    return new Ada_Aba_Learner(
      null,
      dt_to_sql($now),
      dt_to_sql($now),
      null,
      $first_name,
      $last_name,
      $email,
      $slug,
      $challenge_nonce,
      dt_to_sql($challenge_expires_at),
      0
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
      'challenge_nonce' => $this->challenge_nonce,
      'challenge_expires_at' => $this->challenge_expires_at,
      'verified' => $this->verified
    );

    $result = $wpdb->insert($table_name, $data);
    if ($result === false) {
      throw new Ada_Aba_Exception('Failed to insert learner');
    }
  }

  public static function clean_expired_registrations()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $now = new \DateTime();
    $now = dt_to_sql($now);

    $wpdb->query(
      "DELETE FROM $table_name WHERE challenge_expires_at < '$now' AND verified = 0"
    );
  }

  public function verify()
  {
    $this->verified = 1;
    $this->update();
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
      'challenge_nonce' => $this->challenge_nonce,
      'challenge_expires_at' => $this->challenge_expires_at,
      'verified' => $this->verified
    );

    $where = array('id' => $this->id);

    $result = $wpdb->update($table_name, $data, $where);
    if ($result === false) {
      throw new Ada_Aba_Exception('Failed to update learner');
    }
  }
}
