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
		<h2>Courses</h2>
		<div id="ada-aba-courses">
			<?php foreach ($courses as $course) {
				include 'ada-aba-admin-courses-course-fragment.php';
			} ?>
		</div>
	</div>

	<?php include 'ada-aba-admin-courses-add-form-fragment.php' ?>

	<?php include 'ada-aba-admin-courses-edit-form-fragment.php' ?>

</div>