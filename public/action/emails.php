<?php

namespace Ada_Aba\Public\Action;

use Ada_Aba\Includes\Options;
use Ada_Aba\Public\Action\Links;

class Emails
{
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
