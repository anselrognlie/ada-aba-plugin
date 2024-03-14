<?php

namespace Ada_Aba\Public\Action;

use Ada_Aba\Includes\Options;
use Ada_Aba\Public\Action\Links;

class Emails
{
  public static function send_welcome_email($learner)
  {
    $options = Options::get_options();
    if (!$options->get_send_email()) {
      return;
    }

    $first_name = $learner->getFirstName();
    $last_name = $learner->getLastName();
    $email = $learner->getEmail();

    $progress_link = Links\get_progress_link($learner->getSlug());

    $message = self::get_registered_email_content(
      $first_name,
      $last_name,
      $email,
      $progress_link,
    );

    self::mail($email, 'Ada Build Confirmed', $message);
  }

  private static function get_registered_email_content(
    $first_name,
    $last_name,
    $email,
    $progress_link,
  ) {
    ob_start();
    include __DIR__ . '/../partials/registered-email.php';
    return ob_get_clean();
  }

  public static function mail($address, $subject, $content, $headers = [])
  {
    $options = Options::get_options();
    if (!$options->get_send_email()) {
      return;
    }

    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: do-not-reply <do-not-reply@adadevelopersacademy.org>';
    $headers[] = 'Reply-To: do-not-reply <do-not-reply@adadevelopersacademy.org>';

    wp_mail($address, $subject, $content, $headers);
  }
}
