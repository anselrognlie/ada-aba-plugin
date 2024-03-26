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

<div class='ada-aba-survey-survey-question-header'>
  <div class='ada-aba-survey-survey-question-prompt<?php echo $has_description ? '' : ' ada-aba-survey-survey-question-no-description' ?>'>
    <?php echo $prompt_html ?>
  </div>
  <div class='ada-aba-survey-survey-question-description'>
    <?php echo $description_html ?>
  </div>
</div>