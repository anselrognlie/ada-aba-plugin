<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h2>Your email address has been confirmed!</h2>

<p>
  You can start tracking your curriculum progress by
  clicking <a href="<?php echo esc_url($progress_link) ?>">here</a>.
  We hope you enjoy the course!
</p>

<p>
  The first time you visit your progress page, you will be prompted to take a brief survey.
</p>

<p>
  You wil receive an email with the same link.
  Please either retain the email or bookmark the link above for future reference.
  If you lose the link, you can register again with the same email address to receive
  another copy of the email. Your progress will be saved.
</p>
