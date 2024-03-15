<?php

namespace Ada_Aba\Includes;

use Exception;
use Throwable;

class Aba_Exception extends Exception
{
  private $context;

  // Redefine the exception so message isn't optional
  public function __construct($message, $code = 0, $context = [], Throwable $previous = null)
  {
    // make sure everything is assigned properly
    parent::__construct($message, $code, $previous);
    $this->context = $context;
  }

  // custom string representation of object
  public function __toString()
  {
    $trace = Core::jTraceEx($this);
    $context = $this->context;
    $context_str = join('\n', array_reduce(
      array_keys($context),
      function ($acc, $key) use ($context) {
        $acc[] = "$key: $context[$key]";
        return $acc;
      },
      []
    ));

    $msg = !$context ? $trace : "$context_str\n$trace";
    return $msg;
  }
}
