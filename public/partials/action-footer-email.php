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
  Please click the verification link below to complete this action. If you did not take this action, please ignore this email.
</p>

<p>
  <a href="<?php echo $verify_link ?>">Verify link</a>
</p>