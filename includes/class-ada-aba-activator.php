<?php

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
class Ada_Aba_Activator
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
    self::set_default_options($plugin_name);
  }

  private static function create_database_tables()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . Models\Ada_Aba_Learner::$table_name;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    created_at datetime NOT NULL,
    updated_at datetime NOT NULL,
    deleted_at datetime,
    first_name text NOT NULL,
    last_name text NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    slug varchar(255) NOT NULL UNIQUE,
    challenge_nonce varchar(255) NOT NULL UNIQUE,
    challenge_expires_at datetime NOT NULL,
    verified tinyint(1) DEFAULT 0 NOT NULL,
    PRIMARY KEY  (id)
   ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
  }

  private static function set_default_options($plugin_name)
  {
    $options = Ada_Aba_Options::get_default($plugin_name);
    $options->save();
  }
}

