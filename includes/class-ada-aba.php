<?php

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
class Ada_Aba
{

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Ada_Aba_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  // info = 0, warning = 1, errror = 2
  private static $log_level = 0;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct($plugin_name)
  {
    if (defined('ADA_ABA_VERSION')) {
      $this->version = ADA_ABA_VERSION;
    } else {
      $this->version = '1.0.0';
    }
    $this->plugin_name = $plugin_name;

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Ada_Aba_Loader. Orchestrates the hooks of the plugin.
   * - Ada_Aba_i18n. Defines internationalization functionality.
   * - Ada_Aba_Admin. Defines all hooks for the admin area.
   * - Ada_Aba_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies()
  {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ada-aba-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ada-aba-i18n.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ada-aba-options.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ada-aba-exception.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ada-aba-session.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/security/crypto.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ada-aba-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-ada-aba-public.php';

    $this->require_models();

    $this->loader = new Ada_Aba_Loader();

    error_log(Ada_Aba::generate_nonce());
  }

  private function require_models()
  {
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/db-helpers.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-ada-aba-learner.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-ada-aba-course.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-ada-aba-lesson.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-ada-aba-syllabus.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/course/class-ada-aba-course-scalar.php';
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Ada_Aba_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale()
  {

    $plugin_i18n = new Ada_Aba_i18n();

    $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks()
  {

    $plugin_admin = new Ada_Aba_Admin($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    $this->loader->add_action('admin_menu', $plugin_admin, 'add_setup_menu');
    $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
    $this->loader->add_action('rest_api_init', $plugin_admin, 'register_routes');
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks()
  {
    $plugin_name = $this->get_plugin_name();
    $plugin_public = new Ada_Aba_Public($plugin_name, $this->get_version());

    $this->loader->add_shortcode($plugin_name . '-registration-form', $plugin_public, 'shortcode_register_form');
    $this->loader->add_shortcode($plugin_name . '-confirm', $plugin_public, 'shortcode_confirm');

    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    $this->loader->add_action('wp_loaded', $plugin_public, 'handle_page_loaded');
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run()
  {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name()
  {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Ada_Aba_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader()
  {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version()
  {
    return $this->version;
  }

  public static function log($msg, $severity = 0)
  {
    if ($severity < self::$log_level) {
      // don't log if the speficied severity is lower than the current log level
      return;
    }

    error_log($msg);
  }

  public static function set_log_level($level)
  {
    self::$log_level = $level;
  }

  // function to generatre a random string of a given length using the
  // characters A-Z, a-z, 0-9, excluding characters that are treated as vowels
  // (AEIOUWYaeiouwy0134)
  public static function generate_nonce($length = 10)
  {
    $characters = 'BCDFGHJKLMNPQRSTVXZbcdfghjklmnpqrstvxz256789';
    $characters_length = strlen($characters);
    $random_string = [];
    for ($i = 0; $i < $length; $i++) {
      $random_string[] = $characters[rand(0, $characters_length - 1)];
    }
    return implode($random_string);
  }
}
