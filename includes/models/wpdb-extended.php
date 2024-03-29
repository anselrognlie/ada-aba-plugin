<?php

namespace Ada_Aba\Includes\Models;

class Wpdb_Extended
{
  private static $shared_mysql = null;
  private static $shared_host = '';
  private static $shared_name = '';

  private $mysqli;

  public function __construct($host = DB_HOST, $user = DB_USER, $password = DB_PASSWORD, $name = DB_NAME)
  {
    if (self::$shared_mysql === null) {
      self::$shared_mysql = new \mysqli($host, $user, $password, $name);
      self::$shared_host = $host;
      self::$shared_name = $name;
    }

    if (self::$shared_host === $host && self::$shared_name === $name) {
      $this->mysqli = self::$shared_mysql;
    } else {
      $this->mysqli = new \mysqli($host, $user, $password, $name);
    }
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
