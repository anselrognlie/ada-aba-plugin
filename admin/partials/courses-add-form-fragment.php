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

<form id="ada-aba-courses-add-course">
  <h2>Add Course</h2>
  <p>
    <label for="ada-aba-courses-add-course-name">Name</label>
    <input type="text" id="ada-aba-courses-add-course-name" name="name" value="" required>
  </p>
  <p>
    <label for="ada-aba-courses-add-course-url">URL</label>
    <input type="text" id="ada-aba-courses-add-course-url" name="url" value="" required>
  </p>
  <input type="submit" value="Add">
</form>