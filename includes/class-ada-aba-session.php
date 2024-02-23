<?php

class Ada_Aba_Session {
  private $plugin_name;

  private $private_key;

  private $values;

  public static $current = null;

  public static function start($plugin_name, $private_key) {
    if (self::$current !== null) {
      return self::$current;
    }

    $session = new Ada_Aba_Session($plugin_name, $private_key);
    $session->load();

    self::$current = $session;
    return $session;
  }

  public static function close() {
    if (self::$current !== null) {
      self::$current->save();
    }
  }

  public function __construct($plugin_name, $private_key) {
    $this->plugin_name = $plugin_name;
    $this->private_key = $private_key;
    $this->values = [];
  }

  public function load() {
    $encrypted_session = $_COOKIE[$this->plugin_name . '-session'] ?? '';
    if (empty($encrypted_session)) {
      return;
    }

    $raw_session = Crypto\decrypt($encrypted_session, $this->private_key);
    $session = unserialize($raw_session);
    if ($session === false) {
      return;
    }

    $this->values = $session;
  }

  public function get($key) {
    return $this->values[$key] ?? null;
  }

  public function set($key, $value) {
    $this->values[$key] = $value;
  }

  public function save() {
    $raw_session = serialize($this->values);
    $encrypted_session = Crypto\encrypt($raw_session, $this->private_key);
    setcookie($this->plugin_name . '-session', $encrypted_session, time() + MONTH_IN_SECONDS, '/');
    self::$current = null;
  }
}