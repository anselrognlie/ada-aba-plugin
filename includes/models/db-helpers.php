<?php

namespace Ada_Aba\Includes\Models\Db_Helpers;

function dt_to_sql($dt)
{
  return $dt->format('Y-m-d H:i:s');
}
