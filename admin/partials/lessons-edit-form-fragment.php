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

<form id="ada-aba-lessons-edit-lesson">
  <h2>Edit Lesson</h2>
  <p>
    <label for="ada-aba-lessons-edit-lesson-name">Name</label>
    <input type="text" id="ada-aba-lessons-edit-lesson-name" name="name" value="" required>
  </p>
  <p>
    <label for="ada-aba-lessons-edit-lesson-url">URL</label>
    <input type="text" id="ada-aba-lessons-edit-lesson-url" name="url" value="" required>
  </p>
  <input type="hidden" id="ada-aba-lessons-edit-lesson-slug" name="slug" value="" required>
  <input type="submit" value="Update">
  <input type="reset" value="Cancel">
</form>