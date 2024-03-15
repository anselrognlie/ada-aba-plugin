<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Public\Challenge_Actions\Register_Action;
use Ada_Aba\Public\Action\Keys;
use Ada_Aba\Public\Action\Links;

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
    return $this->show_registration_form();
  }

  private function did_registration_post()
  {
    return $this->get_post_value(Keys\ACTION) === Keys\REGISTRATION;
  }

  private function handle_registration_form()
  {
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
      $_POST['error']  = 'Please provide valid values for all inputs.';
      return;
    }

    Core::log(sprintf(
      '%1$s::%2$s: first: %3$s, last: %4$s, email: %5$s',
      __CLASS__,
      __FUNCTION__,
      Core::privy($first_name),
      Core::privy($last_name),
      Core::privy($email),
    ));

    // this is where we would check if the email is already in the database
    // if it is, and the user is not yet verified, we would resend the email
    // (possibly updating the name information). If the user is verified, we
    // would resend the welcome email. Ideally, the displayed message shouldn't
    // indicate whether the email is in the database or not, but for now, we'll
    // just assume it's not in the database.

    // If this learner is already in the database, just send the welcome
    $learner = Learner::get_by_email($email);
    if ($learner) {
      Links\redirect_to_confirmation_page($learner->getSlug());
      return;
    }

    // enqueue the action
    $action = Register_Action::create($email, $first_name, $last_name);
    $action->run();

    Links\redirect_to_confirm_page($action->getSlug());
  }

  private function show_registration_form()
  {
    $form_url = $this->get_ada_build_url();
    return $this->get_registration_form_content(
      $form_url,
      Keys\REGISTRATION,
      self::get_post_value('first_name'),
      self::get_post_value('last_name'),
      self::get_post_value('email'),
      self::get_post_value('error'),
    );
  }

  //
  // output wrappers
  //

  // the function parameters are used within the template
  private function get_registration_form_content(
    $form_action,
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
}
