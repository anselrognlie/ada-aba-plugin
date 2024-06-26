<?php

namespace Ada_Aba\Includes;

/**
 * Fired during plugin activation
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ada_Aba
 * @subpackage Ada_Aba/includes
 * @author     Ada Developers Academy <contact@adadevelopersacademy.org>
 */
class Activator
{

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function activate($plugin_name)
  {
    self::create_database_tables();
    self::ensure_options($plugin_name);
  }

  private static function does_table_have_column($table_name, $column_name)
  {
    global $wpdb;
    $column_exists = $wpdb->get_var("SHOW COLUMNS FROM $table_name LIKE '$column_name'");
    return $column_exists;
  }

  private static function create_database_tables()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $learner_table_name = $wpdb->prefix . Models\Learner::$table_name;
    $course_table_name = $wpdb->prefix . Models\Course::$table_name;
    $lesson_table_name = $wpdb->prefix . Models\Lesson::$table_name;
    $syllabus_table_name = $wpdb->prefix . Models\Syllabus::$table_name;
    $challenge_action_table_name = $wpdb->prefix . Models\Challenge_Action::$table_name;
    $enrollment_table_name = $wpdb->prefix . Models\Enrollment::$table_name;
    $completed_lesson_table_name = $wpdb->prefix . Models\Completed_Lesson::$table_name;
    $question_table_name = $wpdb->prefix . Models\Question::$table_name;
    $survey_table_name = $wpdb->prefix . Models\Survey::$table_name;
    $survey_question_table_name = $wpdb->prefix . Models\Survey_Question::$table_name;
    $surveyed_learner_table_name = $wpdb->prefix . Models\Surveyed_Learner::$table_name;
    $survey_response_table_name = $wpdb->prefix . Models\Survey_Response::$table_name;
    $survey_question_response_table_name = $wpdb->prefix . Models\Survey_Question_Response::$table_name;

    $sql = [];

    $sql[] = "CREATE TABLE IF NOT EXISTS $learner_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      first_name text NOT NULL,
      last_name text NOT NULL,
      email varchar(255) NOT NULL UNIQUE,
      slug varchar(255) NOT NULL UNIQUE,
      PRIMARY KEY  (id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $course_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      name text NOT NULL,
      slug varchar(255) NOT NULL UNIQUE,
      active tinyint(1) DEFAULT 0 NOT NULL,
      url varchar(255) NOT NULL,
      PRIMARY KEY  (id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $lesson_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      name text NOT NULL,
      slug varchar(255) NOT NULL UNIQUE,
      url varchar(255) NOT NULL,
      complete_on_progress tinyint(1) NOT NULL,
      PRIMARY KEY  (id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $syllabus_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      course_id mediumint(9) NOT NULL,
      lesson_id mediumint(9) NOT NULL,
      `order` mediumint(9) NOT NULL,
      slug varchar(255) NOT NULL UNIQUE,
      optional tinyint(1) DEFAULT 0 NOT NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY `course_id_lesson_id` (`course_id`,`lesson_id`),
      FOREIGN KEY (course_id) REFERENCES $course_table_name(id),
      FOREIGN KEY (lesson_id) REFERENCES $lesson_table_name(id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $challenge_action_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      slug varchar(255) NOT NULL UNIQUE,
      email varchar(255) NOT NULL,
      nonce varchar(255) NOT NULL UNIQUE,
      expires_at datetime NOT NULL,
      action_builder text NOT NULL,
      action_payload text NOT NULL,
      PRIMARY KEY  (id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $enrollment_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      learner_id mediumint(9) NOT NULL,
      course_id mediumint(9) NOT NULL,
      slug varchar(255) NOT NULL UNIQUE,
      started_at datetime NOT NULL,
      completed_at datetime,
      completion varchar(255) UNIQUE,
      PRIMARY KEY  (id),
      UNIQUE KEY `course_id_learner_id` (`course_id`,`learner_id`),
      FOREIGN KEY (learner_id) REFERENCES $learner_table_name(id),
      FOREIGN KEY (course_id) REFERENCES $course_table_name(id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $completed_lesson_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      learner_id mediumint(9) NOT NULL,
      lesson_id mediumint(9) NOT NULL,
      slug varchar(255) NOT NULL UNIQUE,
      completed_at datetime NOT NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY `learner_id_lesson_id` (`learner_id`,`lesson_id`),
      FOREIGN KEY (learner_id) REFERENCES $learner_table_name(id),
      FOREIGN KEY (lesson_id) REFERENCES $lesson_table_name(id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $question_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      slug varchar(255) NOT NULL UNIQUE,
      builder text NOT NULL,
      prompt text NOT NULL,
      `description` text NOT NULL,
      `data` text NOT NULL,
      PRIMARY KEY  (id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $survey_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      name text NOT NULL,
      slug varchar(255) NOT NULL UNIQUE,
      active tinyint(1) DEFAULT 0 NOT NULL,
      PRIMARY KEY  (id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $survey_question_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      updated_at datetime NOT NULL,
      deleted_at datetime,
      survey_id mediumint(9) NOT NULL,
      question_id mediumint(9) NOT NULL,
      `order` mediumint(9) NOT NULL,
      slug varchar(255) NOT NULL UNIQUE,
      optional tinyint(1) DEFAULT 0 NOT NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY `survey_id_question_id` (`survey_id`,`question_id`),
      FOREIGN KEY (survey_id) REFERENCES $survey_table_name(id),
      FOREIGN KEY (question_id) REFERENCES $question_table_name(id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $surveyed_learner_table_name (
      learner_slug varchar(255) NOT NULL UNIQUE,
      PRIMARY KEY  (learner_slug)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $survey_response_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      created_at datetime NOT NULL,
      slug varchar(255) NOT NULL UNIQUE,
      survey_id mediumint(9) NOT NULL,
      PRIMARY KEY  (id),
      FOREIGN KEY (survey_id) REFERENCES $survey_table_name(id)
      ) $charset_collate;
    ";

    $sql[] = "CREATE TABLE IF NOT EXISTS $survey_question_response_table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      survey_response_id mediumint(9) NOT NULL,
      question_id mediumint(9) NOT NULL,
      response text NOT NULL,
      PRIMARY KEY  (id),
      UNIQUE KEY `survey_response_id_question_id` (`survey_response_id`,`question_id`),
      FOREIGN KEY (survey_response_id) REFERENCES $survey_response_table_name(id),
      FOREIGN KEY (question_id) REFERENCES $question_table_name(id)
      ) $charset_collate;
    ";

    // dbDelta doesn't officially support foreign keys
    // require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    // dbDelta($sql);

    foreach ($sql as $query) {
      $wpdb->query($query);
    }
  }

  private static function ensure_options($plugin_name)
  {
    $options = Options::get_options($plugin_name);
    $options->save();
  }
}
