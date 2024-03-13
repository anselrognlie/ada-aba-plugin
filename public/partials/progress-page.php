<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<h2>Course Progress</h2>

<?php if (empty($learner_courses)) : ?>
  <p> You are not enrolled in any courses. </p>
  <?php if ($active_course) : ?>
    <p>
      <a href="<?php echo esc_url($enroll_link); ?>">Enroll in <?php echo htmlentities($active_course->getName()) ?></a>
    </p>
  <?php endif; ?>
<?php else : ?>

  <!-- enrollments information -->
  <?php foreach ($learner_courses as $learner_course) : ?>
    <h3><?php echo $learner_course->getCourseName(); ?></h3>
    <p>
      Completed: <?php echo $learner_course->isComplete() ? 'Y' : 'N'; ?>
    </p>
    <ul>
      <?php foreach ($learner_course->getLessons() as $lesson) : ?>
        <li>
          <?php echo htmlentities($lesson->getName()); ?>

          <?php if ($lesson->isOptional()) : ?>
            <span>(Optional)</span>
          <?php endif; ?>

          <?php if ($lesson->isComplete()) : ?>
            <span>✅</span>
          <?php else : ?>
            <a href="#">Finish</a>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
  <?php endforeach; ?>













<?php endif; ?>