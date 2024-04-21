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
class Options
{
  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  private static $instance;

  private $options;

  private const DEFAULT_OPTIONS = [
    'ada-build-page' => -1,
    'error-email' => '',
    'send-email' => true,
    'private-key' => 'replace with a good private key',
    'api-key' => 'replace with a good api key',
    'drop-schema' => false,
    'clear-options' => false,
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

  public static function get_options($plugin_name = '')
  {
    if (self::$instance !== null) {
      return self::$instance;
    }

    if (empty($plugin_name)) {
      throw new Aba_Exception('Options::get_options requires a plugin name');
    }

    $options = get_option($plugin_name . '-settings');
    if ($options === false) {
      return self::get_default($plugin_name);
    }
    self::$instance = new Options($plugin_name, $options);
    return self::$instance;
  }

  public static function get_default($plugin_name)
  {
    return new Options($plugin_name, self::DEFAULT_OPTIONS);
  }

  private function get_with_fallback($key)
  {
    return Core::safe_key($this->options, $key, self::DEFAULT_OPTIONS[$key]);
  }

  public function get_ada_build_page()
  {
    return $this->get_with_fallback('ada-build-page');
  }

  public function set_ada_build_page($post_id)
  {
    return $this->options['ada-build-page'] = $post_id;
  }

  public function get_send_email()
  {
    return isset($this->options['send-email']);
  }

  public function get_private_key()
  {
    return $this->get_with_fallback('private-key');
  }

  public function get_api_key()
  {
    return $this->get_with_fallback('api-key');
  }

  public function get_drop_schema()
  {
    return $this->get_with_fallback('drop-schema');
  }

  public function get_clear_options()
  {
    return $this->get_with_fallback('clear-options');
  }

  public function get_error_email()
  {
    return $this->get_with_fallback('error-email');
  }

  public function save()
  {
    update_option($this->plugin_name . '-settings', $this->options);
  }
}
