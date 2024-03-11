<?php

namespace Ada_Aba\Includes\Models\Db_Helpers;

function dt_to_sql($dt)
{
  return $dt->format('Y-m-d H:i:s');
}

class Transaction {
  public static function start()
  {
    global $wpdb;
    $wpdb->query("START TRANSACTION");
  }
  
  public static function complete()
  {
    global $wpdb;
    $wpdb->query("COMMIT");
  }
  
  public static function rollback()
  {
    global $wpdb;
    $wpdb->query("ROLLBACK");
  }
}
