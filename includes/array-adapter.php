<?php

namespace Ada_Aba\Includes;

class Array_Adapter
{
  private $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function __get($name)
  {
    return $this->data[$name];
  }
}
