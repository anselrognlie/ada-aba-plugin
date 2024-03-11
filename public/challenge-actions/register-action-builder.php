<?php

namespace Ada_Aba\Public\Challenge_Actions;

class Register_Action_Builder extends Action_Builder_Base
{
  public function build($slug, $email, $nonce, $expires_at, $payload)
  {
    $json = json_decode($payload, true);
    return new Register_Action(
      $slug,
      $email,
      $nonce, 
      $expires_at,
      $json['first_name'],
      $json['last_name'],
    );
  }
}
