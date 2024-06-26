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
    <h2>Lessons</h2>
    <div id="ada-aba-lessons">
      <?php foreach ($lessons as $lesson) {
        include 'lessons-lesson-fragment.php';
      } ?>
    </div>
  </div>

  <?php include 'lessons-add-form-fragment.php' ?>

  <?php include 'lessons-edit-form-fragment.php' ?>

</div>