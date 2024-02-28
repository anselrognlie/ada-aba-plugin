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

use Models\Ada_Aba_Course;

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
class Ada_Aba_Admin
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
     * defined in Ada_Aba_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Ada_Aba_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ada-aba-admin.css', array(), $this->version, 'all');
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
     * defined in Ada_Aba_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Ada_Aba_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    // error_log($hook);

    // general scripts
    wp_enqueue_script($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/ada-aba-admin.js', array('jquery'), $this->version, false);

    // page-specific scripts
    if ($hook === 'ada-build-analytics_page_ada-aba-course') {
      wp_enqueue_script($this->plugin_name . '-courses', plugin_dir_url(__FILE__) . 'js/ada-aba-courses.js', array('jquery'), $this->version, false);
    }
  }

  }

  public function add_setup_menu()
  {
    add_menu_page('Ada Build Analytics', 'Ada Build Analytics', 'manage_options', 'ada-aba-setup', array($this, 'setup_page'));
    add_submenu_page('ada-aba-setup', 'Courses', 'Courses', 'manage_options', 'ada-aba-course', array($this, 'course_page'));
    add_submenu_page('ada-aba-setup', 'Lessons', 'Lessons', 'manage_options', 'ada-aba-lesson', array($this, 'course_page'));
    add_submenu_page('ada-aba-setup', 'Syllabus', 'Syllabus', 'manage_options', 'ada-aba-syllabus', array($this, 'course_page'));
  }

  private function get_setup_page_content(
    $pages,
    $confirmation_page_id,
    $registered_page_id,
  ) {
    ob_start();
    include 'partials/ada-aba-admin-display.php';
    return ob_get_clean();
  }

  private function get_courses_page_content(
    $courses,
  ) {
    ob_start();
    include 'partials/ada-aba-admin-courses.php';
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
      'confirmation-page',
      'Confirmation Page',
      array($this, 'sandbox_add_settings_field_select'),
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings-section',
      array(
        'label_for' => 'confirmation-page',
        'description' => 'Page where the [ada-aba-confirmation] shortcode is used.',
        'options' => array($this, 'generate_pages')
      )
    );

    add_settings_field(
      'registered-page',
      'Registered Page',
      array($this, 'sandbox_add_settings_field_select'),
      $this->plugin_name . '-settings',
      $this->plugin_name . '-settings-section',
      array(
        'label_for' => 'registered-page',
        'description' => 'Page where the [ada-aba-registered] shortcode is used.',
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
      'confirmation-page' => null,
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
    Ada_Aba::log(sprintf('%1$s: %2$s', $field_id, $option));

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
    $pages = [new Ada_Aba_Array_Adapter(array(
      'ID' => -1,
      'post_title' => 'Not Set'
    ))];
    $pages = array_merge($pages, get_pages());
    // Ada_Aba::log(print_r($pages, true));
    return $pages;
  }

  public function generate_pages()
  {
    return $this->get_page_options();
  }

  public function setup_page()
  {
    $pages = $this->get_page_options();
    // Ada_Aba::log(print_r($pages, true));
    echo $this->get_setup_page_content($pages, -1, -1);
  }

  public function course_page()
  {
    $courses = Ada_Aba_Course::all();
    echo $this->get_courses_page_content($courses);
  }
}

class Ada_Aba_Array_Adapter
{
  private $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function __get($name)
  {
    return $this->data[$name];
  }
}
