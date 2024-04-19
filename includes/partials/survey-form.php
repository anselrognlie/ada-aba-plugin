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

<form class="ada-aba-survey" method="post">
  <div class="ada-aba-form-inputs">
    <h2><?php echo $survey_name ?></h2>
    <input type="hidden" name="form_state" value="<?php echo $form_state ?>">
    <?php foreach ($questions_html as $question_html) {
      echo $question_html;
    } ?>
    <button>Submit</button>
    <p class="ada-aba-survey-error"><?php echo (!empty($error)) ? htmlentities($error) : '' ?></p>
  </div>
</form>