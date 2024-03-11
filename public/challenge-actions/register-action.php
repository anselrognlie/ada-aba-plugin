<?php

namespace Ada_Aba\Public\Challenge_Actions;

use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Options;
use Ada_Aba\Public\Action\Emails;
use Ada_Aba\Public\Action\Links;
use PhpParser\Node\Scalar\MagicConst\Line;

class Register_Action extends Action_Base
{
  private $first_name;
  private $last_name;

  public function __construct(
    $slug,
    $email,
    $nonce,
    $expires_at,
    $first_name,
    $last_name,
  ) {
    parent::__construct($slug, $email, $nonce, $expires_at);
    $this->first_name = $first_name;
    $this->last_name = $last_name;
  }

  protected function get_builder()
  {
    return new Register_Action_Builder();
  }

  protected function complete_specific()
  {
    $learner = Learner::create(
      $this->first_name,
      $this->last_name,
      $this->getEmail(),
    );

    $failed = false;
    try {
      $failed = $learner->insert();
    } catch (Aba_Exception $e) {
      // potentially add code to let user update or resend email
    }

    if (!$failed) {
      Emails::send_welcome_email($learner);
    }

    Links\redirect_to_confirmation_page($learner->getSlug(), false);
  }

  protected function expired()
  {
    // no special cleanup
  }

  protected function to_payload()
  {
    return json_encode([
      'first_name' => $this->first_name,
      'last_name' => $this->last_name,
    ]);
  }

  public function get_email_content()
  {
    $subject = 'Ada Build Registration';
    $body = $this->get_registration_email_content(
      $this->first_name,
      $this->last_name,
      $this->getEmail(),
    );

    return [$subject, $body];
  }

  public static function create($email, $first_name, $last_name)
  {
    $action = new Register_Action(null, $email, null, null, $first_name, $last_name);
    return $action;
  }

  // the function parameters are used within the template
  private function get_registration_email_content(
    $first_name,
    $last_name,
    $email,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registration-email.php';
    return ob_get_clean();
  }
}
