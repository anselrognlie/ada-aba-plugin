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
 * @subpackage Ada_Aba/includes/partials
 */
?>

<div class="ada-aba-question-editor-panel-wo-option">
	<textarea type="text" rows="4" cols="50"><?php echo htmlentities($option) ?></textarea>
	<button class="ada-aba-question-editor-panel-wo-option-up">Up</button>
	<button class="ada-aba-question-editor-panel-wo-option-down">Down</button>
	<button class="ada-aba-question-editor-panel-wo-option-remove">Remove</button>
</div>