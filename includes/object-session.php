<?php

namespace Ada_Aba\Includes;

use Ada_Aba\Includes\Security\Crypto;

class Object_Session
{
  private $private_key;

  private $values;

  public function __construct($private_key)
  {
    $this->private_key = $private_key;
    $this->values = [];
  }

  public function load($encrypted_data)
  {
    $raw_session = Crypto\decrypt($encrypted_data, $this->private_key);
    $session = unserialize($raw_session);
    if ($session === false) {
      return;
    }

    $this->values = $session;
  }

  public function get($key)
  {
    return array_key_exists($key, $this->values) ? $this->values[$key] : null;
  }

  public function set($key, $value)
  {
    $this->values[$key] = $value;
  }

  public function save()
  {
    $raw_session = serialize($this->values);
    $encrypted_session = Crypto\encrypt($raw_session, $this->private_key);
    return $encrypted_session;
  }
}
