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
<form method="post" action="<?php echo $form_action ?>">
  <h2>Registration</h2>
  <p>
    Ada Build is a free, self-guided curriculum that introduces beginners to 
    Python and coding fundamentals through tutorials and video lessons. 
    Completing this curriculum is a great way to start your coding journey, 
    especially for individuals seeking to strengthen their skills for the Ada 
    application process.
  </p>
  <p>
    The content covers foundational concepts such as the basics of coding 
    languages and getting comfortable with tools.
  </p>
  <label for="first_name">First Name</label>
  <input type="text" id="first_name" name="first_name" value="<?php echo $first_name ?>" required>
  <label for="last_name">Last Name</label>
  <input type="text" id="last_name" name="last_name" value="<?php echo $last_name ?>" required>
  <label for="email">Email</label>
  <input type="email" id="email" name="email" value="<?php echo $email ?>" required>

  <input type="hidden" name="action" value="<?php echo $action ?>">

  <input type="submit" value="Submit">
  <?php
  if (isset($error_message)) {
    echo "<p>$error_message</p>";
  }
  ?>
</form>