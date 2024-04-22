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

<p>
  This view shows your progress in the courses you are enrolled in.
  To complete a lesson, use the links below to navigate to each lesson.
  Each lesson has a location near the end where you can input your Learner ID (shown below)
  when you are ready to mark it as complete. You will receive an email to confirm
  your readiness to mark the lesson complete. After confirming that you are done with
  the lesson, it will become checked off in this view.
</p>
<p>
  Once all required lessons in the course have been completed, a link will be
  provided with which you can request a certificate for the course.
</p>
<p>
  This page may be visited as often as needed as you progress through the lessons.
  We suggest bookmarking it so that you can easily come back to copy your
  Learner ID, which you'll need to mark lessons complete.
</p>

<table class="ada-aba-learner-id-table">
  <tbody>
    <tr>
      <td>
        Learner ID:
      </td>
      <td>
        <?php echo $learner_slug ?>
      </td>
    </tr>
  </tbody>
</table>

<?php if (empty($learner_courses)) : ?>
  <p> You are not enrolled in any courses. </p>
  <?php if ($active_course) : ?>
    <p>
      <a href="<?php echo esc_url($enroll_link); ?>">Enroll in <?php echo htmlentities($active_course->getName()) ?></a>
    </p>
  <?php endif; ?>
<?php else : ?>

  <p class="ada-aba-progress-midway">
    If you previously saved lesson notebooks that have no section for completing the lesson,
    you will need to get a new copy of those notebooks using the lesson links.
    The updated notebooks provide a section for marking the lesson complete.
  </p>

  <!-- enrollments information -->
  <?php foreach ($learner_courses as $learner_course) : ?>
    <div class="ada-aba-progress-table">
      <h3><?php echo $learner_course->getCourseName(); ?>
        <a href="<?php echo $learner_course->getCourseUrl() ?>"><img class="ada-aba-external-link" src="<?php echo plugins_url("$plugin_name/public/assets/img/link-external.png") ?>"></a>
      </h3>
      <p>
        <?php if ($learner_course->isComplete()) : ?>
          All required lessons have been completed: <a href="<?php echo esc_url($learner_course->getRequestCertificateLink()); ?>">Request Certificate</a>
        <?php endif; ?>
      </p>
      <table>
        <thead>
          <tr>
            <th>Lesson</th>
            <th>Completed</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($learner_course->getLessons() as $lesson) : ?>
            <tr>
              <td>
                <a href="<?php echo $lesson->getUrl() ?>"><?php echo htmlentities($lesson->getName()); ?></a>

                <?php if ($lesson->isOptional()) : ?>
                  <span>(Optional)</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($lesson->isComplete()) : ?>
                  <span class="ada-aba-checkbox">☑</span>
                <?php else : ?>
                  <?php if ($lesson->canCompleteOnProgress()) : ?>
                    <a href="<?php echo $lesson->getCompleteLink(); ?>">Finish</a>
                  <?php else : ?>
                    <span class="ada-aba-checkbox">☐</span>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<p class="ada-aba-progress-fit-for-purpose">
  This progress display and the associated certificate are provided for motivational purposes only.
  The primary goal of the course is to experience the material.
  Your completion will not be verified by Ada Developers Academy.
</p>