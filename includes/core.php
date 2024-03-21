<?php

namespace Ada_Aba\Includes;

use Ada_Aba\Admin\Aba_Admin;
use Ada_Aba\Public\Aba_Public;

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
class Core
{

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
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
  const INFO = 0;
  const WARNING = 1;
  const ERROR = 2;

  private static $log_level = self::INFO;

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
   * - Loader. Orchestrates the hooks of the plugin.
   * - i18n. Defines internationalization functionality.
   * - Admin. Defines all hooks for the admin area.
   * - Public. Defines all hooks for the public side of the site.
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
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/i18n.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/options.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/aba-exception.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/session.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/array-adapter.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/security/crypto.php';

    // admin dependencies
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/fragments/courses-fragments.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/courses-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/fragments/lessons-fragments.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/lessons-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/fragments/course-lessons-fragments.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/course-lessons-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/ui/syllabus-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/ui/question-builders-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/fragments/questions-fragments.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/ui/questions-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/questions-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/fragments/surveys-fragments.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/surveys-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/fragments/survey-questions-fragments.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/ui/survey-questions-controller.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/controllers/survey-questions-controller.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/aba-admin.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/aba-public.php';

    $this->require_models();

    $this->loader = new Loader();

    // error_log(self::generate_nonce());
  }

  private function require_models()
  {
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/db-helpers.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/learner.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/course.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/lesson.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/syllabus.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/course-lesson.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/challenge-action.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/enrollment.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/learner-course.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/completed-lesson.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/question.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/survey.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/survey-question.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/relations/survey-question-relations.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/course/course-scalar.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/lesson/lesson-scalar.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/syllabus/syllabus-scalar.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/course-lesson/course-lesson-scalar.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/learner-course/learner-course-progress.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/learner-course/learner-lesson-progress.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/learner-course/learner-course-progress-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/certificate/certificate-details.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/question/question-list-item.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/question/question-scalar.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/survey/survey-scalar.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/survey-question/survey-question-list-item.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/dto/survey-question/survey-question-scalar.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/syllabus-edit-service.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/enrollment-service.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/learner-lesson-service.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/course-lesson-service.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/learner-enrollment-service.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/survey-service.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/survey-question-service.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/services/survey-question-edit-service.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'public/shortcodes/ada-build-shortcode.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'public/challenge-actions/action-builder-base.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/challenge-actions/action-base.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/challenge-actions/register-action.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/challenge-actions/register-action-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/challenge-actions/enroll-action.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/challenge-actions/enroll-action-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/challenge-actions/complete-lesson-action.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/challenge-actions/complete-lesson-action-builder.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'public/action/keys.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/action/links.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/action/emails.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/action/errors.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'public/controllers/completion-controller.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/workflow-base.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/registration-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/action-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/error-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/progress-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/enroll-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/complete-lesson-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/one-shot-email-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/confirmation-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/request-certificate-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/certificate-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/markdown-test-workflow.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/workflows/survey-test-workflow.php';

    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/question-builder-base.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/question-base.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/no-response-question-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/no-response-question.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/short-answer-question-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/short-answer-question.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/paragraph-question-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/paragraph-question.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/with-options-question-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/with-options-question.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/multiple-choice-question-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/multiple-choice-question.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/checkboxes-question-builder.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/checkboxes-question.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/questions/question-palette.php';
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale()
  {

    $plugin_i18n = new I18n();

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

    $plugin_admin = new Aba_Admin($this->get_plugin_name(), $this->get_version());

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
    $plugin_public = new Aba_Public($plugin_name, $this->get_version());

    $this->loader->add_shortcode($plugin_name . '-ada-build', $plugin_public, 'shortcode_ada_build');

    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    $this->loader->add_action('rest_api_init', $plugin_public, 'register_routes');
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
   * @return    Loader    Orchestrates the hooks of the plugin.
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

  public static function log($msg, $severity = self::INFO)
  {
    if ($severity < self::$log_level) {
      // don't log if the specified severity is lower than the current log level
      return;
    }

    error_log($msg);
  }


  public static function log_ex($e, $context = [], $severity = self::ERROR)
  {
    if ($severity < self::$log_level) {
      // don't log if the specified severity is lower than the current log level
      return;
    }

    $trace = self::jTraceEx($e);
    $context_str = join('\n', array_reduce(
      array_keys($context),
      function ($acc, $key) use ($context) {
        $acc[] = "$key: $context[$key]";
        return $acc;
      },
      []
    ));

    $msg = !$context ? $trace : "$context_str\n$trace";

    self::log($msg, $severity);
  }

  public static function privy($msg)
  {
    $len = strlen($msg);
    return "[$len chars]";
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

  public static function safe_key($arr, $key, $default = null)
  {
    return (((bool) $arr) && array_key_exists($key, $arr)) ? $arr[$key] : $default;
  }

  public static function get_ada_build_page()
  {
    $post = get_post(Options::get_options()->get_ada_build_page());
    return $post ? $post->post_name : '@intentionally@illegal@page@name@';
  }

  public static function get_ada_build_url()
  {
    return home_url(self::get_ada_build_page());
  }

  /**
   * jTraceEx() - provide a Java style exception trace
   *              from https://www.php.net/manual/en/exception.gettraceasstring.php#114980
   * @param $exception
   * @param $seen      - array passed to recursive calls to accumulate trace lines already seen
   *                     leave as NULL when calling this function
   * @return string representation of the exception trace
   */
  public static function jTraceEx($e, $seen = null)
  {
    $starter = $seen ? 'Caused by: ' : '';
    $result = array();
    if (!$seen) $seen = array();
    $trace  = $e->getTrace();
    $prev   = $e->getPrevious();
    $result[] = sprintf('%s%s: %s', $starter, get_class($e), $e->getMessage());
    $file = $e->getFile();
    $line = $e->getLine();
    while (true) {
      $current = "$file:$line";
      if (is_array($seen) && in_array($current, $seen)) {
        $result[] = sprintf(' ... %d more', count($trace) + 1);
        break;
      }
      $result[] = sprintf(
        ' at %s%s%s(%s%s%s)',
        count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
        count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
        count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
        $line === null ? $file : basename($file),
        $line === null ? '' : ':',
        $line === null ? '' : $line
      );
      if (is_array($seen))
        $seen[] = "$file:$line";
      if (!count($trace))
        break;
      $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
      $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
      array_shift($trace);
    }
    $result_str = join("\n", $result);
    if ($prev)
      $result_str  .= "\n" . self::jTraceEx($prev, $seen);

    return $result_str;
  }
}
