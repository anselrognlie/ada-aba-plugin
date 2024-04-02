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
    <h2>Reports</h2>
  </div>

  <div>
    <h3>Survey Responses</h3>
    <div id="ada-aba-survey-responses">
    <select id="ada-aba-survey-responses-select">
        <?php foreach ($surveys as $survey) : ?>
          <option value="<?php echo $survey->getSlug(); ?>" <?php echo $survey->getSlug() === $selected_survey->getSlug() ? 'selected' : '' ?>>
            <?php echo $survey->getName(); ?> (<?php echo $survey->getCreatedAt(); ?>)</option>
        <?php endforeach; ?>
      </select>
      <button id="ada-aba-survey-responses-button">Export</button>
    </div>
  </div>

  <div>
    <h3>Course Progress</h3>
    <div id="ada-aba-course-progress">
    <select id="ada-aba-course-progress-select">
        <?php foreach ($courses as $course) : ?>
          <option value="<?php echo $course->getSlug(); ?>" <?php echo $course->getSlug() === $selected_course->getSlug() ? 'selected' : '' ?>>
            <?php echo $course->getName(); ?> (<?php echo $course->getCreatedAt(); ?>)</option>
        <?php endforeach; ?>
      </select>
      <button id="ada-aba-course-progress-button">Export</button>
    </div>
  </div>

</div>