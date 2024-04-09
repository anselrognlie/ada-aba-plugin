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
<p>
  Thank you for registering for Ada Build! We are excited to have you join us!
</p>
<p>
  Before getting started, please take a moment to complete the following survey,
  which should take no more than a few minutes. Your responses will help us
  understand the background of the individuals we are serving.
  No identifying information will be stored with your responses.
</p>
<p>
  <a href="<?php echo esc_url($next_link) ?>">Proceed to survey &raquo;</a>
</p>
