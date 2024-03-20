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

<div class="form-group">
	<label for="options">Options</label>
	<template id="ada-aba-question-editor-panel-wo-option-template">
		<?php echo $template_content ?>
</template>
	<div id="ada-aba-question-editor-panel-wo-options">
		<?php echo $option_content ?>
	</div>
	<button class="ada-aba-question-editor-panel-wo-options-add">Add Option</button>
</div>
<div class="form-group">
	<label for="ada-aba-question-editor-panel-wo-show-other">Show "Other" option</label>
	<input type="checkbox" id="ada-aba-question-editor-panel-wo-show-other" name="show_other" <?php echo $show_other ? ' checked' : '' ?>>
</div>