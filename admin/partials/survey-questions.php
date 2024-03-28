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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">

  <div>
    <h2>Survey Questions</h2>
    <div id="ada-aba-survey-questions">
      <select id="ada-aba-survey-select">
        <?php foreach ($surveys as $survey) : ?>
          <option value="<?php echo $survey->getSlug(); ?>" <?php echo $survey->getSlug() === $selected_survey->getSlug() ? 'selected' : '' ?>>
            <?php echo $survey->getName(); ?></option>
        <?php endforeach; ?>
      </select>
      <div>
        <h3>Questions</h3>
        <h4>Available Questions</h4>
        <div id="ada-aba-available-questions">
          <?php foreach ($available_questions as $question) {
            include 'survey-question-available-question-fragment.php';
          } ?>
        </div>

        <h4>Survey Questions</h4>
        <div id="ada-aba-survey-survey-questions">
          <?php foreach ($survey_question_relations as $survey_question_relation) {
            include 'survey-question-question-fragment.php';
          } ?>
        </div>
      </div>
    </div>
  </div>

</div>