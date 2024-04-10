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

<p class="ada-aba-course" data-ada-aba-course-slug="<?php echo $course->getSlug() ?>">
  <span><?php echo $course->getName() ?></span>
  <span>[<?php echo $course->getSlug() ?>]</span>
  <?php if ($course->isActive()) : ?>
    <span>(Active)</span>
  <?php else : ?>
    <span><a href="#" class="ada-aba-courses-activate">Activate</a></span>
  <?php endif ?>
  <span><a href="#" class="ada-aba-courses-edit">Edit</a></span>
  <span><a href="<?php echo $course->getUrl() ?>" target="_blank" class="ada-aba-courses-url">Url</a></span>
  <span><a href="#" class="ada-aba-courses-delete">Delete</a></span>
</p>