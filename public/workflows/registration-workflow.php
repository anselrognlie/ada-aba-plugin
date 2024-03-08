<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Options;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Public\Workflows\Keys;

class Registration_Workflow extends Workflow_Base
{
  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
  }

  public function can_handle_load_precise()
  {
    return $this->did_registration_post();
  }

  public function handle_load()
  {
      $this->handle_registration_form();
  }

  public function can_handle_page()
  {
    return true;
  }

  public function handle_page()
  {
    if ($this->did_registration_post()) {
      return $this->handle_registration_post();
    } else if ($this->is_resend_request()) {
      return $this->handle_resend_request();
    } else {
      return $this->show_registration_form();
    }
  }

  private function did_registration_post()
  {
    return $this->get_post_value(Keys\ACTION) === Keys\REGISTRATION;
  }

  private function is_resend_request()
  {
    return isset($_GET[Keys\RESEND]);
  }

  private function handle_registration_form()
  {
    $this->clean_expired_registrations();

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
      $_POST['error']  = 'Please provide valid values for all inputs.';
      return;
    }

    Core::log(sprintf(
      '%1$s: first: %2$s, last: %3$s, email: %4$s',
      __FUNCTION__,
      $first_name,
      $last_name,
      $email
    ));

    $learner = Learner::create(
      $first_name,
      $last_name,
      $email,
    );

    try {
      $learner->insert();
    } catch (Aba_Exception $e) {
      // potentially add code to let user update or resend email
    }

    // for now, always send the email
    $this->send_registration_email($learner);
  }

  private function get_resend_link($email)
  {
    return home_url($this->get_ada_build_page()) . '?' . Keys\RESEND . "=$email";
  }

  private function get_verify_link($nonce)
  {
    return home_url($this->get_ada_build_page()) . '?' . Keys\VERIFY . "=$nonce";
  }

  private function handle_registration_post()
  {
    $email = urlencode(self::get_post_value('email'));
    $resend_link = $this->get_resend_link($email);

    return $this->get_registration_posted_content($resend_link);
  }

  private function handle_resend_request()
  {
    $this->clean_expired_registrations();

    $email_raw = $_GET[Keys\RESEND];
    $email = urldecode($email_raw);
    $resend_link = $this->get_resend_link($email);
    Core::log(sprintf(
      '%1$s: email: %2$s',
      __FUNCTION__,
      $email_raw
    ));

    $learner = Learner::get_by_email($email_raw);
    if ($learner) {
      $this->send_registration_email($learner);
    }

    return $this->get_registration_resend_content($resend_link);
  }

  private function show_registration_form()
  {
    $form_url = $this->get_current_url();
    return $this->get_registration_form_content(
      $form_url,
      $form_url,
      Keys\REGISTRATION,
      self::get_post_value('first_name'),
      self::get_post_value('last_name'),
      self::get_post_value('email'),
      self::get_post_value('error'),
    );
  }

  private function send_registration_email($learner)
  {
    $options = Options::get_options($this->plugin_name);

    $challenge = $learner->getChallengeNonce();

    $first_name = $learner->getFirstName();
    $last_name = $learner->getLastName();
    $email = $learner->getEmail();
    $verify_link = $this->get_verify_link($challenge);
    Core::log(sprintf(
      '%1$s: first: %2$s, last: %3$s, email: %4$s, verify_link: %5$s',
      __FUNCTION__,
      $first_name,
      $last_name,
      $email,
      $verify_link
    ));

    $message = $this->get_registration_email_content(
      $first_name,
      $last_name,
      $email,
      $verify_link,
    );
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if ($options->get_send_email()) {
      wp_mail($email, 'Ada Build Registration', $message, $headers);
    }
  }

  //
  // output wrappers
  //

  // the function parameters are used within the template
  private function get_registration_posted_content(
    $resend_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registration-posted.php';
    return ob_get_clean();
  }

  // the function parameters are used within the template
  private function get_registration_resend_content(
    $resend_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registration-resend.php';
    return ob_get_clean();
  }

  // the function parameters are used within the template
  private function get_registration_email_content(
    $first_name,
    $last_name,
    $email,
    $verify_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registration-email.php';
    return ob_get_clean();
  }

  // the function parameters are used within the template
  private function get_registration_form_content(
    $form_url,
    $verify_link,
    $action,
    $first_name,
    $last_name,
    $email,
    $error_message,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registration-form.php';
    return ob_get_clean();
  }

  //
  // Utility functions
  //

  private function clean_expired_registrations()
  {
    Learner::clean_expired_registrations();
  }
}
