<?php

namespace Ada_Aba\Public\Challenge_Actions;

use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Services\Enrollment_Service;
use Ada_Aba\Public\Action\Links;

class Enroll_Action extends Action_Base
{
  private $learner_slug;

  public function __construct($slug, $email, $nonce, $expires_at, $learner_slug)
  {
    parent::__construct($slug, $email, $nonce, $expires_at);
    $this->learner_slug = $learner_slug;
  }

  protected function get_builder()
  {
    return new Enroll_Action_Builder();
  }

  protected function complete_specific()
  {
    $learner = Learner::get_by_slug($this->learner_slug);

    if (!$learner) {
      throw new Aba_Exception('Could not get learner');
    }

    $enrollment_service = new Enrollment_Service($this->learner_slug);
    $enrollment_service->enroll_in_default();

    Links\redirect_to_progress_page($this->learner_slug, halt: false);
  }

  protected function expired()
  {
    // no special cleanup
  }

  protected function to_payload()
  {
    return json_encode([
      'learner_slug' => $this->learner_slug,
    ]);
  }

  public function get_email_content()
  {
    $subject = 'Ada Build Enrollment';
    $body = $this->get_enroll_email_content();

    return [$subject, $body];
  }

  public static function create($email, $learner_slug)
  {
    $action = new Enroll_Action(null, $email, null, null, $learner_slug);
    return $action;
  }

  // the function parameters are used within the template
  private function get_enroll_email_content()
  {
    ob_start();
    include __DIR__ . '/../partials/enroll-email.php';
    return ob_get_clean();
  }
}
