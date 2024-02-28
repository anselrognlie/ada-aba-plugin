<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ada_Aba
 * @subpackage Ada_Aba/includes
 * @author     Ada Developers Academy <contact@adadevelopersacademy.org>
 */
class Ada_Aba_Deactivator
{

  /**
   * Short Description. (use period)
   *
   * Long Description.
   *
   * @since    1.0.0
   */
  public static function deactivate($plugin_name)
  {
    self::drop_database_tables();
    self::clear_options($plugin_name);
  }

  private static function drop_database_tables()
  {
    global $wpdb;

    $learner_table_name = $wpdb->prefix . Models\Ada_Aba_Learner::$table_name;
    $course_table_name = $wpdb->prefix . Models\Ada_Aba_Course::$table_name;
    $lesson_table_name = $wpdb->prefix . Models\Ada_Aba_Lesson::$table_name;
    $syllabus_table_name = $wpdb->prefix . Models\Ada_Aba_Syllabus::$table_name;

    $wpdb->query("DROP TABLE IF EXISTS $learner_table_name");
    $wpdb->query("DROP TABLE IF EXISTS $course_table_name");
    $wpdb->query("DROP TABLE IF EXISTS $lesson_table_name");
    $wpdb->query("DROP TABLE IF EXISTS $syllabus_table_name");
  }

  private static function clear_options($plugin_name)
  {
    global $wpdb;

    $table_name = $wpdb->prefix . 'options';

    $setting_name = $plugin_name . '-settings';
    $cmd = "DELETE FROM $table_name WHERE option_name = '$setting_name'";
    Ada_Aba::log($cmd);
    $wpdb->query($cmd);
  }
}
