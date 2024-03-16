<?php

namespace Ada_Aba\Public;

use Ada_Aba\Admin\Controllers\Completion_Controller;
use Ada_Aba\Includes\Aba_Exception;
use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Options;
use Ada_Aba\Public\Action\Errors;
use Ada_Aba\Public\Shortcodes\Ada_Build_Shortcode;
use Ada_Aba\Public\Workflows\Registration_Workflow;
use Ada_Aba\Public\Workflows\Action_Workflow;
use Ada_Aba\Public\Workflows\Certificate_Workflow;
use Ada_Aba\Public\Workflows\Complete_Lesson_Workflow;
use Ada_Aba\Public\Workflows\Confirmation_Workflow;
use Ada_Aba\Public\Workflows\Enroll_Workflow;
use Ada_Aba\Public\Workflows\Request_Certificate_Workflow;
use Exception;

use function Ada_Aba\Public\Action\Links\redirect_to_error_page;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/public
 * @author     Ada Developers Academy <contact@adadevelopersacademy.org>
 */
class Aba_Public
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  private $load_handlers = [];

  private $completion_routes;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->register_load_handlers();
    Options::get_options($plugin_name);  // prime the instance for the rest of the plugin
  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ada-aba-public.css', array(), $this->version, 'all');
  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ada-aba-public.js', array('jquery'), $this->version, false);

    wp_localize_script(
      $this->plugin_name,
      'ada_aba_vars',
      array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),  // from https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
      )
    );
  }

  public function register_routes()
  {
    // register course routes
    $this->completion_routes = new Completion_Controller($this->plugin_name);
    $this->completion_routes->register_routes();
  }

  private function register_load_handlers()
  {
    $this->load_handlers = array(
      new Action_Workflow($this->plugin_name),
      new Enroll_Workflow($this->plugin_name),
      new Complete_Lesson_Workflow($this->plugin_name),
      new Request_Certificate_Workflow($this->plugin_name),
      new Certificate_Workflow($this->plugin_name),
      new Confirmation_Workflow($this->plugin_name),
      new Registration_Workflow($this->plugin_name),
    );
  }

  // registered in Core
  public function handle_page_loaded()
  {
    try {
      foreach ($this->load_handlers as $handler) {
        if ($handler->can_handle_load()) {
          return $handler->handle_load();
        }
      }
    } catch (Aba_Exception $e) {
      Core::log($e);
      redirect_to_error_page(Errors\UNKNOWN);
    } catch (Exception $e) {
      Core::log_ex($e);
      redirect_to_error_page(Errors\UNKNOWN);
    }
  }

  public function shortcode_ada_build()
  {
    try {
      $code = new Ada_Build_Shortcode($this->plugin_name);
      return $code->run();
    } catch (Aba_Exception $e) {
      Core::log($e);
      return $this->get_unknown_error_content();
    } catch (Exception $e) {
      Core::log_ex($e);
      return $this->get_unknown_error_content();
    }
  }

  private function get_unknown_error_content()
  {
    $error = '0x' . dechex(Errors\UNKNOWN);  // used in template
    ob_start();
    include __DIR__ . '/partials/error-page.php';
    return ob_get_clean();
  }
}
