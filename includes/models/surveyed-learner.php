<?php

namespace Ada_Aba\Includes\Models;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Aba_Exception;

use function Ada_Aba\Includes\Models\Db_Helpers\dt_to_sql;

class Surveyed_Learner
{
  private $learner_slug;

  public static $table_name = 'ada_aba_surveyed_learner';

  public function __construct(
    $learner_slug,
  ) {
    $this->learner_slug = $learner_slug;
  }

  // Getters
  public function getLearnerSlug()
  {
    return $this->learner_slug;
  }

  // Setters
  public function setLearnerSlug($learner_slug)
  {
    $this->learner_slug = $learner_slug;
  }

  public static function contains($learner_slug)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $row = $wpdb->get_row(
      $wpdb->prepare(
        "SELECT 1 FROM $table_name WHERE learner_slug = %s",
        $learner_slug
      ),
      'ARRAY_A'
    );

    return (bool)$row;
  }

  public static function insert($learner_slug)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . self::$table_name;

    $data = array(
      'learner_slug' => $learner_slug,
    );

    $result = $wpdb->insert($table_name, $data);
    return ($result !== false);
  }
}
