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

<div class="ada-aba-survey-survey-question<?php echo $required ? ' ada-aba-survey-survey-question-required' : '' ?>" data-ada-aba-question-type="<?php echo $builder_slug ?>">
  <?php echo $content ?>
</div>