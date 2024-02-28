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
<?php foreach ($courses as $course): ?>
	<p class="ada-aba-course" data-ada-aba-course-slug="<?php echo $course->getSlug() ?>">
		<span><?php echo $course->getName() ?></span>
		<?php if ($course->isActive()): ?>
			<span>(Active)</span>
		<?php else: ?>
			<span><a href="#">Activate</a></span>
		<?php endif ?>
	</p>
<?php endforeach ?>
</div>

<form>
	<h2>Add Course</h2>
  <label for="course_name">Name</label>
  <input type="text" id="course_name" name="name" value="" required>
  <input type="submit" value="Add">
</form>

</div>