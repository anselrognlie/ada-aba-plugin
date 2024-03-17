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
    <h3><?php echo $learner_course->getCourseName(); ?>
      <a href="<?php echo $learner_course->getCourseUrl() ?>"><img src="<?php echo plugins_url("$plugin_name/public/assets/img/link-external.png") ?>"></a></h3>
    <p>
      <?php if ($learner_course->isComplete()) : ?>
        <a href="<?php echo esc_url($learner_course->getRequestCertificateLink()); ?>">(Request Certificate)</a>
      <?php endif; ?>
    </p>
    <ul>
      <?php foreach ($learner_course->getLessons() as $lesson) : ?>
        <li>
          <a href="<?php echo $lesson->getUrl() ?>"><?php echo htmlentities($lesson->getName()); ?></a>

          <?php if ($lesson->isOptional()) : ?>
            <span>(Optional)</span>
          <?php endif; ?>

          <?php if ($lesson->isComplete()) : ?>
            <span>✅</span>
          <?php else : ?>
            <a href="<?php echo $lesson->getCompleteLink(); ?>">Finish</a>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    <?php endforeach; ?>













  <?php endif; ?>