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

<p class="ada-aba-survey" data-ada-aba-survey-slug="<?php echo $survey->getSlug() ?>">
	<span><?php echo htmlentities($survey->getName()) ?></span>
	<span>[<?php echo htmlentities($survey->getSlug()) ?>]</span>
	<?php if ($survey->isActive()) : ?>
		<span>(Active)</span>
	<?php else : ?>
		<span><a href="#" class="ada-aba-surveys-activate">Activate</a></span>
	<?php endif ?>
	<span><a href="#" class="ada-aba-surveys-edit">Edit</a></span>
	<span><a href="#" class="ada-aba-surveys-delete">Delete</a></span>
</p>