<?php

namespace Ada_Aba\Public\Challenge_Actions;

abstract class Action_Builder_Base
{
  abstract public function build($slug, $email, $nonce, $expires_at, $payload);
}
