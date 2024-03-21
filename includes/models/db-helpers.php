<?php

namespace Ada_Aba\Includes\Models\Db_Helpers;

use Ada_Aba\Includes\Aba_Exception;

function dt_to_sql($dt)
{
  return $dt->format('Y-m-d H:i:s');
}

class Transaction {
  private static $instance = null;

  private $depth;
  private $ok;

  private function __construct()
  {
    $this->depth = 0;
    $this->ok = true;
  }

  public static function start()
  {
    if (self::$instance === null) {
      self::$instance = new self();
      self::$instance->start_internal();
    } else {
      self::$instance->depth++;
    }
  }

  private function start_internal()
  {
    global $wpdb;

    if ($this->depth === 0) {
      $wpdb->query("START TRANSACTION");
    }

    $this->depth += 1;
  }

  private static function rollback_and_throw()
  {
    global $wpdb;
    $wpdb->query("ROLLBACK");
    throw new Aba_Exception("Unbalanced transaction completion");
  }
  
  public static function complete()
  {
    if (self::$instance === null) {
      self::rollback_and_throw();
    }

    self::$instance->complete_internal();
  }
  
  private function complete_internal()
  {
    $this->depth -= 1;

    $this->handle_complete();
  }

  public static function rollback()
  {
    if (self::$instance === null) {
      self::rollback_and_throw();
    }

    self::$instance->rollback_internal();
  }

  private function rollback_internal()
  {
    $this->ok = false;
    $this->depth -= 1;

    $this->handle_complete();
  }

  private function handle_complete()
  {
    global $wpdb;

    if ($this->depth === 0) {
      if ($this->ok) {
        $wpdb->query("COMMIT");
      } else {
        $wpdb->query("ROLLBACK");
      }
    }
  }
}
