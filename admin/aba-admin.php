<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/admin
 */

namespace Ada_Aba\Admin;

use Ada_Aba\Includes\Core;
use Ada_Aba\Includes\Array_Adapter;
use Ada_Aba\Includes\Models\Course;
use Ada_Aba\Includes\Models\Lesson;
use Ada_Aba\Includes\Services\Syllabus_Edit_Service;
use Ada_Aba\Admin\Controllers\Courses_Controller;
use Ada_Aba\Admin\Controllers\Lessons_Controller;
use Ada_Aba\Admin\Controllers\Course_Lessons_Controller;
use Ada_Aba\Admin\Controllers\UI\Question_Builders_Controller;
use Ada_Aba\Admin\Controllers\UI\Questions_Controller as Questions_UI_Controller;
use Ada_Aba\Admin\Controllers\Questions_Controller;
use Ada_Aba\Admin\Controllers\UI\Syllabus_Controller;
use Ada_Aba\Includes\Dto\Question\Question_List_Item;
use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Includes\Questions\Question_Palette;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/admin
 * @author     Ada Developers Academy <contact@adadevelopersacademy.org>
 */
class Aba_Admin
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

  private $course_routes;
  private $lesson_routes;
  private $course_lesson_routes;
  private $syllabus_routes;
  private $question_builders_routes;
  private $questions_ui_routes;
  private $questions_routes;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
   * Register the stylesheets for the admin area.
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

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ada-aba-admin.css', array(), $this->version, 'all');
  }

  private function enqueue_api_script($script_id, $script_path, $dependencies, $version, $args)
  {
    wp_enqueue_script($script_id, $script_path, $dependencies, $version, $args);
    wp_localize_script(
      $script_id,
      'ada_aba_vars',
      array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
      )
    );
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts($hook)
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

    // error_log($hook);

    // general scripts
    wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/ada-aba-admin.js', array('jquery'), $this->version, false);

    // page-specific scripts
    if ($hook === 'ada-build-analytics_page_ada-aba-course') {
      $courses_script = $this->plugin_name . '-courses';
      wp_enqueue_script($courses_script, plugin_dir_url(__FILE__) . "js/$courses_script.js", array('jquery'), $this->version, false);

      $api_courses_script = $this->plugin_name . '-api-courses';
      $this->enqueue_api_script($api_courses_script, plugin_dir_url(__FILE__) . "js/api/$api_courses_script.js", array('jquery'), $this->version, false);
    }

    if ($hook === 'ada-build-analytics_page_ada-aba-lesson') {
      $lessons_script = $this->plugin_name . '-lessons';
      wp_enqueue_script($lessons_script, plugin_dir_url(__FILE__) . "js/$lessons_script.js", array('jquery'), $this->version, false);

      $api_lessons_script = $this->plugin_name . '-api-lessons';
      $this->enqueue_api_script($api_lessons_script, plugin_dir_url(__FILE__) . "js/api/$api_lessons_script.js", array('jquery'), $this->version, false);
    }

    if ($hook === 'ada-build-analytics_page_ada-aba-syllabus') {
      $course_lessons_script = $this->plugin_name . '-course-lessons';
      wp_enqueue_script($course_lessons_script, plugin_dir_url(__FILE__) . "js/$course_lessons_script.js", array('jquery'), $this->version, false);

      $api_courses_script = $this->plugin_name . '-api-courses';
      $this->enqueue_api_script($api_courses_script, plugin_dir_url(__FILE__) . "js/api/$api_courses_script.js", array('jquery'), $this->version, false);
      $api_lessons_script = $this->plugin_name . '-api-lessons';
      $this->enqueue_api_script($api_lessons_script, plugin_dir_url(__FILE__) . "js/api/$api_lessons_script.js", array('jquery'), $this->version, false);
      $api_course_lessons_script = $this->plugin_name . '-api-course-lessons';
      $this->enqueue_api_script($api_course_lessons_script, plugin_dir_url(__FILE__) . "js/api/$api_course_lessons_script.js", array('jquery'), $this->version, false);
      $api_course_lessons_script = $this->plugin_name . '-api-syllabus';
      $this->enqueue_api_script($api_course_lessons_script, plugin_dir_url(__FILE__) . "js/api/$api_course_lessons_script.js", array('jquery'), $this->version, false);
    }

    if ($hook === 'ada-build-analytics_page_ada-aba-questions') {
      $question_plugins = array(
        'question-base-plugin',
        'no-response-question-plugin',
        'short-answer-question-plugin',
        'paragraph-question-plugin',
        'question-palette'
      );
      foreach ($question_plugins as $plugin) {
        $plugin_script = $this->plugin_name . '-' . $plugin;
        wp_enqueue_script($plugin_script, plugin_dir_url(__FILE__) . "js/questions/$plugin_script.js", array('jquery'), $this->version, false);
      }

      $questions_script = $this->plugin_name . '-questions';
      wp_enqueue_script($questions_script, plugin_dir_url(__FILE__) . "js/$questions_script.js", array('jquery'), $this->version, false);

      $api_questions_script = $this->plugin_name . '-api-questions';
      $this->enqueue_api_script($api_questions_script, plugin_dir_url(__FILE__) . "js/api/$api_questions_script.js", array('jquery'), $this->version, false);
      $api_question_builders_script = $this->plugin_name . '-api-question-builders';
      $this->enqueue_api_script($api_question_builders_script, plugin_dir_url(__FILE__) . "js/api/$api_question_builders_script.js", array('jquery'), $this->version, false);
    }
  }

  public function register_routes()
  {
    // register course routes
    $this->course_routes = new Courses_Controller($this->plugin_name);
    $this->course_routes->register_routes();

    // register lesson routes
    $this->lesson_routes = new Lessons_Controller($this->plugin_name);
    $this->lesson_routes->register_routes();

    // register course-lesson routes
    $this->course_lesson_routes = new Course_Lessons_Controller($this->plugin_name);
    $this->course_lesson_routes->register_routes();

    // register syllabus ui routes
    $this->syllabus_routes = new Syllabus_Controller($this->plugin_name);
    $this->syllabus_routes->register_routes();

    // register question builders ui routes
    $this->question_builders_routes = new Question_Builders_Controller($this->plugin_name);
    $this->question_builders_routes->register_routes();

    // register questions ui routes
    $this->questions_ui_routes = new Questions_UI_Controller($this->plugin_name);
    $this->questions_ui_routes->register_routes();

    // register questions routes
    $this->questions_routes = new Questions_Controller($this->plugin_name);
    $this->questions_routes->register_routes();
  }

  public function add_setup_menu()
  {
    add_menu_page('Ada Build Analytics', 'Ada Build Analytics', 'manage_options', 'ada-aba-setup', array($this, 'setup_page'));
    add_submenu_page('ada-aba-setup', 'Courses', 'Courses', 'manage_options', 'ada-aba-course', array($this, 'course_page'));
    add_submenu_page('ada-aba-setup', 'Lessons', 'Lessons', 'manage_options', 'ada-aba-lesson', array($this, 'lesson_page'));
    add_submenu_page('ada-aba-setup', 'Syllabus', 'Syllabus', 'manage_options', 'ada-aba-syllabus', array($this, 'syllabus_page'));
    add_submenu_page('ada-aba-setup', 'Questions', 'Questions', 'manage_options', 'ada-aba-questions', array($this, 'question_page'));
  }

  private function get_setup_page_content()
  {
    $slug = Core::generate_nonce();

    ob_start();
    include 'partials/display.php';
    return ob_get_clean();
  }

  private function get_courses_page_content(
    $courses,
  ) {
    ob_start();
    include 'partials/courses.php';
    return ob_get_clean();
  }

  private function get_lessons_page_content(
    $lessons,
  ) {
    ob_start();
    include 'partials/lessons.php';
    return ob_get_clean();
  }

  private function get_syllabuses_page_content(
    $courses,
    $selected_course,
    $course_lessons,
    $available_lessons
  ) {
    ob_start();
    include 'partials/syllabuses.php';
    return ob_get_clean();
  }

  private function get_questions_page_content($questions, $builders)
  {
    ob_start();
    include 'partials/questions.php';
    return ob_get_clean();
  }

  public function register_settings()
  {
    // Here we are going to register our setting.
    register_setting(
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings',
      array($this, 'sandbox_register_setting')
    );

    add_settings_section(
      $this->plugin_name . '-settings-section',
      'Settings',
      array($this, 'sandbox_add_settings_section'),
      $this->plugin_name . '-settings'
    );

    add_settings_field(
      'ada-build-page',
      'Ada Build Page',
      array($this, 'sandbox_add_settings_field_select'),
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings-section',
      array(
        'label_for' => 'ada-build-page',
        'description' => 'Page where the [ada-aba-ada-build] shortcode is used. All actions pass through this page. It is automatically updated when the host page is loaded.',
        'options' => array($this, 'generate_pages')
      )
    );

    add_settings_field(
      'private-key',
      'Private Key',
      array($this, 'sandbox_add_settings_field_input_text'),
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings-section',
      array(
        'label_for' => 'private-key',
        'default' => '',
        'description' => 'Key used to encrypt sessions, etc'
      )
    );

    add_settings_field(
      'send-email',
      'Send registration email',
      array($this, 'sandbox_add_settings_field_single_checkbox'),
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings-section',
      array(
        'label_for' => 'send-email',
        'description' => 'Uncheck to disable sending registration emails (during testing).'
      )
    );
  }

  public function sandbox_register_setting($input)
  {

    $new_input = array();
    $valid_options = array(
      'learner-confirmation-page' => null,
      'confirm-page' => null,
      'registered-page' => null,
      'private-key' => null,
      // 'private-key' => array(
      //   'sanitizer' => array($this, 'sanitize_hex'),
      //   'label' => 'Private Key',
      //   'restore' => array($this, 'get_previous_private_key'),
      // ),
      'send-email' => null
    );

    if (isset($input)) {
      // Loop trough each input and only include known fields
      foreach ($input as $key => $value) {
        if (array_key_exists($key, $valid_options)) {
          $sanitizer = $valid_options[$key];
          if (!empty($sanitizer)) {
            $value = call_user_func(
              $sanitizer['sanitizer'],
              $value,
              $key,
              $sanitizer['label'],
              $sanitizer['restore']
            );
          }
          $new_input[$key] = $value;
        }
      }
    }

    return $new_input;
  }

  private function get_previous_private_key()
  {
    $options = get_option($this->plugin_name . '-settings');
    $option = '';

    if (!empty($options['private-key'])) {
      $option = $options['private-key'];
    }

    return $option;
  }

  private function sanitize_hex($input, $field_id, $label, $get_previous_value)
  {

    if (!preg_match('/^[0-9a-fA-F]+$/', $input)) {
      $label = esc_html($label);
      add_settings_error(
        $field_id,
        esc_attr($field_id), //becomes part of id attribute of error message
        "$label should be hexadecimal",
        'error'
      );

      $input = call_user_func($get_previous_value);
    }

    return $input;
  }

  public function sandbox_add_settings_section()
  {
    return;
  }

  public function sandbox_add_settings_field_single_checkbox($args)
  {

    $field_id = $args['label_for'];
    $field_description = $args['description'];

    $options = get_option($this->plugin_name . '-settings');
    $option = 0;

    if (!empty($options[$field_id])) {

      $option = (int) $options[$field_id];
    }

?>

    <label for="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
      <input type="checkbox" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" <?php checked($option); ?> value="1" />
      <span class="description"><?php echo esc_html($field_description); ?></span>
    </label>

  <?php

  }

  public function sandbox_add_settings_field_select($args)
  {

    $field_id = $args['label_for'];
    $field_description = $args['description'];

    $options = get_option($this->plugin_name . '-settings');
    $option = 0;

    if (!empty($options[$field_id])) {
      $option = (int) $options[$field_id];
    }
    Core::log(sprintf('%1$s: %2$s', $field_id, $option));

    $pages = call_user_func($args['options']);

  ?>
    <fieldset>
      <label for="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
        <select id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
          <?php foreach ($pages as $page) : ?>
            <option value="<?php echo esc_html($page->ID) ?>" <?php echo ($page->ID === $option) ? 'selected' : '' ?>><?php echo esc_html($page->post_title) ?></option>
          <?php endforeach; ?>
        </select>
        <span class="description"><?php echo esc_html($field_description); ?></span>
      </label>
    </fieldset>

  <?php

  }

  public function sandbox_add_settings_field_input_text($args)
  {

    $field_id = $args['label_for'];
    $field_default = $args['default'];
    $field_description = $args['description'];

    $options = get_option($this->plugin_name . '-settings');
    $option = $field_default;

    if (!empty($options[$field_id])) {

      $option = $options[$field_id];
    }

  ?>

    <label for="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
      <input type="text" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="<?php echo esc_attr($option); ?>" class="regular-text" />
      <div><span class="description"><?php echo esc_html($field_description); ?></span></div>
    </label>

<?php

  }

  private function get_page_options()
  {
    $pages = [new Array_Adapter(array(
      'ID' => -1,
      'post_title' => 'Not Set'
    ))];
    $pages = array_merge($pages, get_pages());
    // Core::log(print_r($pages, true));
    return $pages;
  }

  public function generate_pages()
  {
    return $this->get_page_options();
  }

  public function setup_page()
  {
    $pages = $this->get_page_options();
    // Core::log(print_r($pages, true));
    echo $this->get_setup_page_content();
  }

  public function course_page()
  {
    $courses = Course::all();
    echo $this->get_courses_page_content($courses);
  }

  public function lesson_page()
  {
    $lessons = Lesson::all();
    echo $this->get_lessons_page_content($lessons);
  }

  private static function get_selected_course($courses)
  {
    $selected_course_arr = array_filter($courses, function ($course) {
      return $course->isActive();
    });
    // error_log(print_r($selected_course_arr, true));
    if (count($selected_course_arr) === 1) {
      $selected_course = $selected_course_arr[0];
    } else {
      $selected_course = null;
    }

    return $selected_course;
  }

  public function syllabus_page()
  {
    $courses = Course::all();
    $selected_course = self::get_selected_course($courses);
    $syllabus_edit_service = new Syllabus_Edit_Service($selected_course->getSlug());

    $course_lessons = $syllabus_edit_service->get_course_lessons();
    $available_lessons = $syllabus_edit_service->get_available_lessons();
    echo $this->get_syllabuses_page_content($courses, $selected_course, $course_lessons, $available_lessons);
  }

  public function question_page()
  {
    $question_models = Question::all();
    $questions = array_map(function ($question) {
      return new Question_List_Item($question);
    }, $question_models);

    $palette = new Question_Palette();
    $builders = $palette->getBuilders();
    // error_log(print_r($builders, true));
    echo $this->get_questions_page_content($questions, $builders);
  }
}
