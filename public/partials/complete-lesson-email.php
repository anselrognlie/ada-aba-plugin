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

<p>
  Great job completing the lesson, <?php echo htmlentities($lesson_name); ?>!
</p>

<p>
  If you did not intend to mark the lesson as complete, or if you are not ready to mark it as complete yet,
  just ignore this email and the status will not change.
  Click <a href="<?php echo esc_url($progress_link) ?>">here</a> to return to your progress view.
</p>