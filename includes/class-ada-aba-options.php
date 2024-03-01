<?php

namespace Ada_Aba\Includes;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ada_Aba
 * @subpackage Ada_Aba/includes
 * @author     Ada Developers Academy <contact@adadevelopersacademy.org>
 */
class Ada_Aba_Options
{
  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  private $options;

  private const DEFAULT_OPTIONS = [
    'confirmation-page' => -1,
    'registered-page' => -1,
    'send-email' => 1,
    'private-key' => 'replace with a good private key',
  ];

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  private function __construct($plugin_name, $options)
  {
    $this->plugin_name = $plugin_name;
    $this->options = $options;  // php uses value semantics for arrays
  }

  public static function get_options($plugin_name)
  {
    $options = get_option($plugin_name . '-settings');
    if ($options === false) {
      return self::get_default($plugin_name);
    }
    return new Ada_Aba_Options($plugin_name, $options);
  }

  public static function get_default($plugin_name)
  {
    return new Ada_Aba_Options($plugin_name, self::DEFAULT_OPTIONS);
  }

  public function get_confirmation_page()
  {
    return $this->options['confirmation-page'];
  }

  public function get_registered_page()
  {
    return $this->options['registered-page'];
  }

  public function get_send_email()
  {
    return isset($this->options['send-email']);
  }

  public function get_private_key()
  {
    return isset($this->options['private-key']);
  }

  public function save()
  {
    update_option($this->plugin_name . '-settings', $this->options);
  }
}
