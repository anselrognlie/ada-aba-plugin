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

<p class="ada-aba-lesson" data-ada-aba-lesson-slug="<?php echo $lesson->getSlug() ?>">
  <span><?php echo $lesson->getName() ?></span>
  <span><a href="#" class="ada-aba-available-lessons-add">Add</a></span>
</p>