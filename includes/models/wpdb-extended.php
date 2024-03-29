<?php

namespace Ada_Aba\Includes\Models;

class Wpdb_Extended
{
  private $mysqli;

  public function __construct()
  {
    $this->mysqli = new \mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  }

  public function get_raw_results($query)
  {
    $empty = ['columns' => [], 'rows' => []];

    $mysqli  = $this->mysqli;

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
      return ['columns' => $columns, 'rows' => $rows,];
    }

    return false;
  }
}
