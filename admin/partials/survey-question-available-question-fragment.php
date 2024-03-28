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

<p class="ada-aba-question" data-ada-aba-question-slug="<?php echo $question->getSlug() ?>">
  <span><?php echo htmlentities($question->getDisplay()) ?></span>
  <span><a href="#" class="ada-aba-available-questions-add">Add</a></span>
</p>