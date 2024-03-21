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
		<h2>Surveys</h2>
		<div id="ada-aba-surveys">
			<?php foreach ($surveys as $survey) {
				include 'surveys-survey-fragment.php';
			} ?>
		</div>
	</div>

	<?php include 'surveys-add-form-fragment.php' ?>

	<?php include 'surveys-edit-form-fragment.php' ?>

</div>