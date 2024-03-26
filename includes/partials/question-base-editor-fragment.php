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

<div class="ada-aba-question-editor">
	<input type="hidden" id="ada-aba-question-editor-panel-builder-slug" name="builder" value="<?php echo $builder_slug ?>" />
	<div class="ada-aba-question-editor__prompt">
		<label for="ada-aba-question-editor-panel-prompt">Prompt</label>
		<textarea id="ada-aba-question-editor-panel-prompt" name="prompt" rows="4" cols="50"><?php echo htmlentities($prompt) ?></textarea>
	</div>
	<div class="ada-aba-question-editor__description">
		<label for="ada-aba-question-editor-panel-description">Description</label>
		<textarea id="ada-aba-question-editor-panel-description" name="description" rows="4" cols="50"><?php echo htmlentities($description) ?></textarea>
	</div>
	<?php echo $derived_editor ?>
</div>