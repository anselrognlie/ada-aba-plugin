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
  Please click the verification link in the email sent to the address you provided to complete this action.
</p>

<p>
  If you do not see the email, please check your spam folder.
</p>

<p>
  To resend the email, click <a href="<?php echo $resend_link ?>">here</a>.
</p>