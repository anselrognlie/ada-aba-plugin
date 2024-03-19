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
		<h2>Questions</h2>
		<div id="ada-aba-questions">
			<div id="ada-aba-questions-list">
				<?php foreach ($questions as $question) {
					include 'questions-question-fragment.php';
				
				} ?>
			</div>
			<select id="ada-aba-question-builders">
				<?php foreach ($builders as $builder) : ?>
					<option value="<?php echo $builder->get_slug(); ?>">
						<?php echo $builder->get_display_name(); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<button id="ada-aba-question-new">New</button>
			<form id="ada-aba-question-editor">
				<input type="hidden" id="ada-aba-question-editor-id" name="id" value="" />
				<p>
					<label for="ada-aba-question-editor-slug">Slug</label>
					<input type="text" id="ada-aba-question-editor-slug" name="slug" value="" />
					<span>*leave blank to auto-assign</span>
				</p>
				<div id="ada-aba-question-editor-panel"></div>
				<button id="ada-aba-question-editor-save">Save</button>
				<button id="ada-aba-question-editor-preview">Preview</button>
				<button id="ada-aba-question-editor-cancel">Cancel</button>
			</form>
			<div id="ada-aba-question-preview-panel"></div>
		</div>
	</div>

</div>