<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/includes/partials
 */
?>

<?php echo $base_content ?>
<div class="ada-aba-survey-survey-question-options">
  <?php foreach ($options_html as $option_html) {
    echo $option_html;
  } ?>
  <?php echo $other_html ?>
</div>