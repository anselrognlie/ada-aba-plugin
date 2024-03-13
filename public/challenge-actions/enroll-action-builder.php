<?php

namespace Ada_Aba\Public\Challenge_Actions;

class Enroll_Action_Builder extends Action_Builder_Base
{
  public function build($slug, $email, $nonce, $expires_at, $payload)
  {
    $json = json_decode($payload, true);
    return new Enroll_Action(
      $slug,
      $email,
      $nonce, 
      $expires_at,
      $json['learner_slug'],
    );
  }
}
