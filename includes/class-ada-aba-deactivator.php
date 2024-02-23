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

    $table_name = $wpdb->prefix . 'ada_aba_learner';

    $wpdb->query("DROP TABLE IF EXISTS $table_name");
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
