<?php

use Ada_Aba\Includes\Core;

class Wpdb_Extended extends wpdb {
  private $last_columns = [];

  public function __construct(){
    parent::__construct( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
  }
 
  public function get_raw_results($query) {
    $empty = ['columns' => [], 'rows' => []];

    $mysqli  = $this->dbh;
    if (! $mysqli instanceof mysqli) {
      return false;
    }

    if ($result = $mysqli->query($query)) {

      if ($result === true) {
        return $empty;
      }

      /* Get field information for all columns */
      $columns = [];
      while ($finfo = $result->fetch_field()) {
  
          $table = $finfo->orgtable;
          $column = $finfo->name;
          $columns[] = "$table.$column";
      }
      $rows = $result->fetch_all(MYSQLI_NUM);
      $result->close();
      return ['columns' => $columns, 'rows' => $rows, ];
    }
  
    return false;
  }
}

global $wpdbx;
$wpdbx = new Wpdb_Extended();
