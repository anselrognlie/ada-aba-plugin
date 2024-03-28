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
 * @subpackage Ada_Aba/admin/partials
 */
?>

<p class="ada-aba-survey-question" data-ada-aba-survey-question-slug="<?php echo $survey_question_relation->getSlug() ?>">
  <span>
    <?php echo htmlentities($survey_question_relation->getDisplay()) ?>
    <?php echo $survey_question_relation->isOptional() ? '(Optional)' : '' ?>
  </span>
  <span><a href="#" class="ada-aba-survey-questions-remove">Remove</a></span>
  <span><a href="#" class="ada-aba-survey-questions-up">Up</a></span>
  <span><a href="#" class="ada-aba-survey-questions-down">Down</a></span>
  <span><a href="#" class="ada-aba-survey-questions-toggle-option">Toggle</a></span>
</p>