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

<form id="ada-aba-surveys-edit-survey">
  <h2>Edit Survey</h2>
  <p>
    <label for="ada-aba-surveys-edit-survey-name">Name</label>
    <input type="text" id="ada-aba-surveys-edit-survey-name" name="name" value="" required>
  </p>
  <input type="hidden" id="ada-aba-surveys-edit-survey-slug" name="slug" value="" required>
  <input type="submit" value="Update">
  <input type="reset" value="Cancel">
</form>