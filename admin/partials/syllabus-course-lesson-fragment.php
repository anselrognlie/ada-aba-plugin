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

<p class="ada-aba-course-lesson" data-ada-aba-course-lesson-slug="<?php echo $course_lesson->getSlug() ?>">
	<span>
		<?php echo $course_lesson->getLesson()->getName() ?>
		<?php echo $course_lesson->isOptional() ? '(Optional)' : '' ?>
		</span>
	<span><a href="#" class="ada-aba-course-lessons-remove">Remove</a></span>
	<span><a href="#" class="ada-aba-course-lessons-up">Up</a></span>
	<span><a href="#" class="ada-aba-course-lessons-down">Down</a></span>
	<span><a href="#" class="ada-aba-course-lessons-toggle-option">Toggle</a></span>
</p>