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

<form id="ada-aba-lessons-add-lesson">
  <h2>Add Lessons</h2>
  <p>
    <label for="ada-aba-lessons-add-lesson-name">Name</label>
    <input type="text" id="ada-aba-lessons-add-lesson-name" name="name" value="" required>
  </p>
  <p>
    <label for="ada-aba-lessons-add-lesson-url">URL</label>
    <input type="text" id="ada-aba-lessons-add-lesson-url" name="url" value="" required>
  </p>
  <p>
    <input type="checkbox" id="ada-aba-lessons-add-lesson-complete" name="complete" value="">
    <label for="ada-aba-lessons-add-lesson-complete">Show complete on progress</label>
  </p>
  <input type="submit" value="Add">
</form>