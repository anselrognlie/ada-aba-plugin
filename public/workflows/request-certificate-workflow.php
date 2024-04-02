<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Models\Enrollment;
use Ada_Aba\Includes\Services\Learner_Enrollment_Service;
use Ada_Aba\Public\Action\Emails;
use Ada_Aba\Includes\Action\Keys;

use Ada_Aba\Public\Action\Links;

class Request_Certificate_Workflow extends One_Shot_Email_Workflow
{
  private $email;
  private $subject;
  private $body;

  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name, Keys\REQUEST_CERTIFICATE);
  }

  protected function get_base_url()
  {
    $enrollment_slug = $_GET[Keys\REQUEST_CERTIFICATE];
    return Links\get_request_certificate_link($enrollment_slug);
  }

  protected function get_email()
  {
    return $this->email;
  }

  protected function get_subject()
  {
    return $this->subject;
  }

  protected function get_body()
  {
    return $this->body;
  }

  protected function handle_request_load_internal()
  {
    $enrollment_slug = $_GET[Keys\REQUEST_CERTIFICATE];
    $enrollment = Enrollment::get_by_slug($enrollment_slug);

    $service = new Learner_Enrollment_Service();
    $learner = $service->get_learner_by_enrollment_slug($enrollment_slug);

    return $this->get_request_certificate_email($learner, $enrollment);
  }

  protected function handle_request_page_internal()
  {
    $enrollment_slug = $_GET[Keys\REQUEST_CERTIFICATE];

    $service = new Learner_Enrollment_Service();
    $learner = $service->get_learner_by_enrollment_slug($enrollment_slug);

    $progress_link = Links\get_progress_link($learner->getSlug());

    return $this->get_request_certificate_page_content($progress_link);
  }

  public function get_request_certificate_email($learner, $enrollment)
  {
    $first_name = $learner->getFirstName();
    $last_name = $learner->getLastName();
    $email = $learner->getEmail();
    $completion = $enrollment->getCompletion();

    $cert_link = Links\get_certificate_link($completion);

    $message = $this->get_request_certificate_email_content(
      $first_name,
      $last_name,
      $email,
      $cert_link,
    );

    $this->email = $email;
    $this->subject = 'Ada Build Certificate Request';
    $this->body = $message;
  }

  private function get_request_certificate_email_content(
    $first_name,
    $last_name,
    $email,
    $cert_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/request-certificate-email.php';
    return ob_get_clean();
  }

  private function get_request_certificate_page_content($progress_link)
  {
    ob_start();
    include __DIR__ . '/../partials/request-certificate-page.php';
    return ob_get_clean();
  }
}
