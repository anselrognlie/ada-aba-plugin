<?php

namespace Ada_Aba\Public\Challenge_Actions;

class Complete_Lesson_Action_Builder extends Action_Builder_Base
{
  public function build($slug, $email, $nonce, $expires_at, $payload)
  {
    $json = json_decode($payload, true);
    return new Complete_Lesson_Action(
      $slug,
      $email,
      $nonce, 
      $expires_at,
      $json['lesson_slug'],
      $json['learner_slug'],
    );
  }
}
