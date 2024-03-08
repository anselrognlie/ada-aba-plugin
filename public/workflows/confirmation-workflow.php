<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Models\Learner;
use Ada_Aba\Includes\Options;
use Ada_Aba\Includes\Aba_Exception;

use Ada_Aba\Public\Workflows\Keys;

class Confirmation_Workflow extends Workflow_Base
{
  public function __construct($plugin_name)
  {
    parent::__construct($plugin_name);
  }

  public function can_handle_load_precise()
  {
    return $this->is_in_get(Keys\VERIFY);
  }

  public function handle_load()
  {
    $this->handle_learner_confirmation();
  }

  public function can_handle_page()
  {
    return $this->is_in_get(Keys\CONFIRMATION);
  }

  public function handle_page()
  {
    if ($this->is_user_confirmed()) {
      return $this->handle_learner_confirmation_success();
    } else {
      return $this->handle_learner_confirmation_failed();
    }
  }

  private function is_user_confirmed()
  {
    return isset($_GET['u']);
  }

  private function get_confirmed_user()
  {
    return $_GET['u'];
  }

  private function get_verify_code()
  {
    return isset($_GET[Keys\VERIFY]) ? $_GET[Keys\VERIFY] : '';
  }

  private function get_confirmation_link($slug)
  {
    return home_url($this->get_ada_build_page()) . '?' . Keys\CONFIRMATION
      . (empty($slug) ? '' : "&u=$slug");
  }

  private function get_progress_link($slug)
  {
    return home_url($this->get_ada_build_page()) . "?u=$slug";
  }

  public function handle_learner_confirmation()
  {
    $this->clean_expired_registrations();

    $verify_code = $this->get_verify_code();
    Core::log(sprintf(
      '%1$s: verify_code: %2$s',
      __FUNCTION__,
      $verify_code
    ));

    if (empty($verify_code)) {
      // let the request fall through to actually rendering the page, which
      // should encounter our shortcode
      return;
    }

    $learner = Learner::get_by_verify_code($verify_code);
    $failed = false;

    if ($learner) {
      $user = $learner->getSlug();

      try {
        $learner->verify();
      } catch (Aba_Exception $e) {
        $failed = true;
      }
    } else {
      $user = '';
    }

    if (!$failed && !empty($learner)) {
      $this->send_registered_email($learner);
    }

    $target = $this->get_confirmation_link($user);
    wp_redirect($target);
    exit;
  }

  private function handle_learner_confirmation_success()
  {
    $user = $this->get_confirmed_user();
    $progress_link = $this->get_progress_link($user) ?? '';
    return $this->get_registered_content($progress_link);
  }

  private function handle_learner_confirmation_failed()
  {
    return $this->get_registered_error_content();
  }

  private function send_registered_email($learner)
  {
    $options = Options::get_options($this->plugin_name);

    $first_name = $learner->getFirstName();
    $last_name = $learner->getLastName();
    $email = $learner->getEmail();
    Core::log(sprintf(
      '%1$s: first: %2$s, last: %3$s, email: %4$s',
      __FUNCTION__,
      $first_name,
      $last_name,
      $email,
    ));

    $progress_link = $this->get_progress_link($learner->getSlug());

    $message = $this->get_registered_email_content(
      $first_name,
      $last_name,
      $email,
      $progress_link,
    );
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if ($options->get_send_email()) {
      wp_mail($email, 'Ada Build Confirmed', $message, $headers);
    }
  }

  //
  // output wrappers
  //

  // the function parameters are used within the template
  private function get_registered_email_content(
    $first_name,
    $last_name,
    $email,
    $progress_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registered-email.php';
    return ob_get_clean();
  }

  private function get_registered_content(
    $progress_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registered-page.php';
    return ob_get_clean();
  }

  private function get_registered_error_content()
  {
    ob_start();
    include __DIR__ . '/../partials/registered-error.php';
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
