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
		<h2>Syllabuses</h2>
		<div id="ada-aba-syllabuses">
			<select id="ada-aba-course-select">
				<?php foreach ($courses as $course) : ?>
					<option value="<?php echo $course->getSlug(); ?>" <?php echo $course->getSlug() === $selected_course->getSlug() ? 'selected' : '' ?>>
						<?php echo $course->getName(); ?></option>
				<?php endforeach; ?>
			</select>
			<div>
				<h3>Lessons</h3>
				<h4>Available Lessons</h4>
				<div id="ada-aba-available-lessons">
					<?php foreach ($available_lessons as $lesson) {
						include 'syllabus-available-lesson-fragment.php';
					} ?>
				</div>

				<h4>Course Lessons</h4>
				<div id="ada-aba-course-lessons">
					<?php foreach ($course_lessons as $course_lesson) {
						include 'syllabus-course-lesson-fragment.php';
					} ?>
				</div>
			</div>
		</div>
	</div>

</div>