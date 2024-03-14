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
  Congratulations on completing the course! You can view your completion
  certificate at the following link: <a href="<?php echo esc_url($cert_link) ?>">Certificate Link</a>
</p>

<p>
  We hope you enjoyed the course!
</p>