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
use Ada_Aba\Includes\Dto\Question\Question_List_Item;
use Ada_Aba\Includes\Dto\Survey_Question\Survey_Question_List_Item;
use Ada_Aba\Includes\Models\Question;
use Ada_Aba\Includes\Models\Survey;
use Ada_Aba\Includes\Questions\Question_Palette;
use Ada_Aba\Includes\Services\Survey_Question_Edit_Service;

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

  private $routes_controller_classes;
  private $routes_controllers;

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

    $this->routes_controller_classes = array(
      'Ada_Aba\Admin\Controllers\Courses_Controller',
      'Ada_Aba\Admin\Controllers\Lessons_Controller',
      'Ada_Aba\Admin\Controllers\Course_Lessons_Controller',
      'Ada_Aba\Admin\Controllers\UI\Syllabus_Controller',
      'Ada_Aba\Admin\Controllers\UI\Question_Builders_Controller',
      'Ada_Aba\Admin\Controllers\UI\Questions_Controller',
      'Ada_Aba\Admin\Controllers\Questions_Controller',
      'Ada_Aba\Admin\Controllers\Surveys_Controller',
      'Ada_Aba\Admin\Controllers\Survey_Questions_Controller',
      'Ada_Aba\Admin\Controllers\UI\Survey_Questions_Controller',
      'Ada_Aba\Admin\Controllers\Queries_Controller',
    );
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

      $api_course_lessons_script = $this->plugin_name . '-api-course-lessons';
      $this->enqueue_api_script($api_course_lessons_script, plugin_dir_url(__FILE__) . "js/api/$api_course_lessons_script.js", array('jquery'), $this->version, false);
      $api_course_lessons_script = $this->plugin_name . '-api-syllabus';
      $this->enqueue_api_script($api_course_lessons_script, plugin_dir_url(__FILE__) . "js/api/$api_course_lessons_script.js", array('jquery'), $this->version, false);
    }

    if ($hook === 'ada-build-analytics_page_ada-aba-question') {
      $question_plugins = array(
        'question-base-plugin',
        'no-response-question-plugin',
        'short-answer-question-plugin',
        'paragraph-question-plugin',
        'with-options-question-plugin',
        'multiple-choice-question-plugin',
        'checkboxes-question-plugin',
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

    if ($hook === 'ada-build-analytics_page_ada-aba-survey') {
      $surveys_script = $this->plugin_name . '-surveys';
      wp_enqueue_script($surveys_script, plugin_dir_url(__FILE__) . "js/$surveys_script.js", array('jquery'), $this->version, false);

      $api_surveys_script = $this->plugin_name . '-api-surveys';
      $this->enqueue_api_script($api_surveys_script, plugin_dir_url(__FILE__) . "js/api/$api_surveys_script.js", array('jquery'), $this->version, false);
    }

    if ($hook === 'ada-build-analytics_page_ada-aba-survey-question') {
      $survey_questions_script = $this->plugin_name . '-survey-questions';
      wp_enqueue_script($survey_questions_script, plugin_dir_url(__FILE__) . "js/$survey_questions_script.js", array('jquery'), $this->version, false);

      $api_survey_questions_script = $this->plugin_name . '-api-survey-questions';
      $this->enqueue_api_script($api_survey_questions_script, plugin_dir_url(__FILE__) . "js/api/$api_survey_questions_script.js", array('jquery'), $this->version, false);
      $api_survey_questions_script = $this->plugin_name . '-api-survey-questions-ui';
      $this->enqueue_api_script($api_survey_questions_script, plugin_dir_url(__FILE__) . "js/api/$api_survey_questions_script.js", array('jquery'), $this->version, false);
    }

    if ($hook === 'ada-build-analytics_page_ada-aba-utilities') {
      $page_script = $this->plugin_name . '-utilities';
      wp_enqueue_script($page_script, plugin_dir_url(__FILE__) . "js/$page_script.js", array('jquery'), $this->version, false);

      $api_script = $this->plugin_name . '-api-execute-query';
      $this->enqueue_api_script($api_script, plugin_dir_url(__FILE__) . "js/api/$api_script.js", array('jquery'), $this->version, false);
    }

    if ($hook === 'ada-build-analytics_page_ada-aba-reports') {
      $page_script = $this->plugin_name . '-reports';
      wp_enqueue_script($page_script, plugin_dir_url(__FILE__) . "js/$page_script.js", array('jquery'), $this->version, false);

      $api_script = $this->plugin_name . '-links';
      $this->enqueue_api_script($api_script, plugin_dir_url(__FILE__) . "js/links/$api_script.js", array('jquery'), $this->version, false);
    }
  }

  public function register_routes()
  {
    foreach ($this->routes_controller_classes as $controller_class) {
      $controller = new $controller_class($this->plugin_name);
      $controller->register_routes();
      $this->routes_controllers[] = $controller;
    }
  }

  public function add_setup_menu()
  {
    add_menu_page('Ada Build Analytics', 'Ada Build Analytics', 'manage_options', 'ada-aba-setup', array($this, 'setup_page'));
    add_submenu_page('ada-aba-setup', 'Courses', 'Courses', 'manage_options', 'ada-aba-course', array($this, 'course_page'));
    add_submenu_page('ada-aba-setup', 'Lessons', 'Lessons', 'manage_options', 'ada-aba-lesson', array($this, 'lesson_page'));
    add_submenu_page('ada-aba-setup', 'Syllabus', 'Syllabus', 'manage_options', 'ada-aba-syllabus', array($this, 'syllabus_page'));
    add_submenu_page('ada-aba-setup', 'Surveys', 'Surveys', 'manage_options', 'ada-aba-survey', array($this, 'survey_page'));
    add_submenu_page('ada-aba-setup', 'Questions', 'Questions', 'manage_options', 'ada-aba-question', array($this, 'question_page'));
    add_submenu_page('ada-aba-setup', 'Survey Questions', 'Survey Questions', 'manage_options', 'ada-aba-survey-question', array($this, 'survey_question_page'));
    add_submenu_page('ada-aba-setup', 'Reports', 'Reports', 'manage_options', 'ada-aba-reports', array($this, 'reports_page'));
    add_submenu_page('ada-aba-setup', 'Utilities', 'Utilities', 'manage_options', 'ada-aba-utilities', array($this, 'utilities_page'));
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

  private function get_surveys_page_content(
    $surveys,
  ) {
    ob_start();
    include 'partials/surveys.php';
    return ob_get_clean();
  }

  private function get_survey_questions_page_content(
    $surveys,
    $selected_survey,
    $survey_question_relations,
    $available_questions
  ) {
    ob_start();
    include 'partials/survey-questions.php';
    return ob_get_clean();
  }

  private function get_reports_page_content($surveys, $selected_survey, $courses, $selected_course)
  {
    ob_start();
    include 'partials/reports.php';
    return ob_get_clean();
  }

  private function get_utilities_page_content()
  {
    ob_start();
    include 'partials/utilities.php';
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
        'options' => $this->generate_pages(...),
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
      'api-key',
      'API Key',
      array($this, 'sandbox_add_settings_field_input_text'),
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings-section',
      array(
        'label_for' => 'api-key',
        'default' => '',
        'description' => 'Key used for secure API access to public routes'
      )
    );

    add_settings_field(
      'drop-schema',
      'Delete plugin tables on deactivate',
      array($this, 'sandbox_add_settings_field_single_checkbox'),
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings-section',
      array(
        'label_for' => 'drop-schema',
        'description' => 'Check to remove the tables associated with this plugin when it is deactivated.'
      )
    );

    add_settings_field(
      'clear-options',
      'Delete plugin options on deactivate',
      array($this, 'sandbox_add_settings_field_single_checkbox'),
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings-section',
      array(
        'label_for' => 'clear-options',
        'description' => 'Check to remove the options associated with this plugin when it is deactivated.'
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
      'api-key' => null,
      'drop-schema' => null,
      'clear-options' => null,
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
    $pages = array_merge($pages, get_pages(array(
      'post_status' => ['publish', 'private'],
    )));
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

  private static function get_selected_activatable_record($activatable_records)
  {
    $selected_activatable_record_arr = array_filter($activatable_records, function ($activatable_record) {
      return $activatable_record->isActive();
    });
    // error_log(print_r($selected_activatable_record_arr, true));
    if (count($selected_activatable_record_arr) === 1) {
      $selected_activatable_record = $selected_activatable_record_arr[0];
    } else {
      $selected_activatable_record = null;
    }

    return $selected_activatable_record;
  }

  public function syllabus_page()
  {
    $courses = Course::all();
    $selected_course = self::get_selected_activatable_record($courses);
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

  public function survey_page()
  {
    $surveys = Survey::all();
    echo $this->get_surveys_page_content($surveys);
  }

  public function survey_question_page()
  {
    $surveys = Survey::all();
    $selected_survey = self::get_selected_activatable_record($surveys);
    $selected_slug = $selected_survey->getSlug();
    $survey_question_edit_service = new Survey_Question_Edit_Service();

    $survey_questions = array_map(function ($survey_question_relation) {
      return new Survey_Question_List_Item($survey_question_relation);
    }, $survey_question_edit_service->get_survey_questions($selected_slug));

    $available_questions = array_map(function ($question) {
      return new Question_List_Item($question);
    }, $survey_question_edit_service->get_available_questions($selected_slug));

    echo $this->get_survey_questions_page_content($surveys, $selected_survey, $survey_questions, $available_questions);
  }

  public function reports_page()
  {
    $surveys = Survey::all();
    $selected_survey = self::get_selected_activatable_record($surveys);
    $courses = Course::all();
    $selected_course = Course::get_active_course();
    echo $this->get_reports_page_content($surveys, $selected_survey, $courses, $selected_course);
  }

  public function utilities_page()
  {
    echo $this->get_utilities_page_content();
  }
}
