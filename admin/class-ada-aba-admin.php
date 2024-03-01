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
use Dto\Course\Ada_Aba_Course_Scalar;

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
      $courses_script = $this->plugin_name . '-courses';
      wp_enqueue_script($courses_script, plugin_dir_url(__FILE__) . 'js/ada-aba-courses.js', array('jquery'), $this->version, false);
      wp_localize_script(
        $courses_script,
        'ada_aba_vars',
        array(
          'root' => esc_url_raw(rest_url()),
          'nonce' => wp_create_nonce('wp_rest'),
        )
      );
    }
  }

  public function register_routes()
  {
    // Here we are registering our route for a collection of products.
    register_rest_route('ada-aba/v1', '/products', array(
      // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
      'methods'  => WP_REST_Server::READABLE,
      // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
      'callback' => array($this, 'get_products'),
    ));

    // // Here we are registering our route for single products. The (?P<id>[\d]+) is our path variable for the ID, which, in this example, can only be some form of positive number.
    // register_rest_route( 'ada-aba/v1', '/products/(?P<id>[\d]+)', array(
    //   // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
    //   'methods'  => WP_REST_Server::READABLE,
    //   // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
    //   'callback' => 'prefix_get_product',
    // ) );

    $ROUTE_VERSION = 'v1';
    register_rest_route("{$this->plugin_name}/$ROUTE_VERSION", '/courses', array(
      array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => array($this, 'get_courses'),
      ),
      array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::CREATABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => array($this, 'add_course'),
      ),
    ));

    register_rest_route("{$this->plugin_name}/$ROUTE_VERSION", '/courses/(?P<slug>[\w\d]+)', array(
      array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::DELETABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => array($this, 'delete_course'),
      ),
      array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => array($this, 'get_course'),
      ),
      array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::EDITABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => array($this, 'update_course'),
      ),
    ));

    register_rest_route("{$this->plugin_name}/$ROUTE_VERSION", '/courses/(?P<slug>[\w\d]+)/activate', array(
      array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::EDITABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => array($this, 'activate_course'),
      ),
    ));

  }

  public function get_courses($request)
  {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this resource.', 'my-text-domain'), array('status' => 401));
    }

    $courses = Ada_Aba_Course::all();

    $serialized_courses = array_map(function ($course) {
      return new Ada_Aba_Course_Scalar($course);
    }, $courses);

    $response = array(
      'json' => $serialized_courses,
    );

    // error_log(print_r($request->get_headers(), true));
    if ($request->get_header('accept') === 'text/html') {
      $html_courses = array_map(function ($course) {
        return $this->get_courses_fragment($course);
      }, $courses);

      $response['html'] = implode($html_courses);
    }

    return rest_ensure_response($response);
  }

  public function get_course($request)
  {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this resource.', 'my-text-domain'), array('status' => 401));
    }

    $slug = $request['slug'];
    Ada_Aba::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $course = Ada_Aba_Course::get_by_slug($slug);
    return rest_ensure_response(new Ada_Aba_Course_Scalar($course));
  }

  public function add_course($request)
  {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this resource.', 'my-text-domain'), array('status' => 401));
    }

    $course = Ada_Aba_Course::create($request->get_param('name'));
    $course->insert();
    return rest_ensure_response(new Ada_Aba_Course_Scalar($course));
  }

  public function delete_course($request)
  {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this resource.', 'my-text-domain'), array('status' => 401));
    }

    $slug = $request['slug'];
    Ada_Aba::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $course = Ada_Aba_Course::get_by_slug($slug);
    if ($course) {
      $course->delete();
    }
    return rest_ensure_response(new Ada_Aba_Course_Scalar($course));
  }

  public function activate_course($request)
  {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this resource.', 'my-text-domain'), array('status' => 401));
    }

    $slug = $request['slug'];
    Ada_Aba::log(sprintf('%1$s: slug: %2$s', __FUNCTION__, $slug));

    $course = Ada_Aba_Course::activate($slug);

    return rest_ensure_response(new Ada_Aba_Course_Scalar($course));
  }

  public function update_course($request)
  {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this resource.', 'my-text-domain'), array('status' => 401));
    }

    $slug = $request['slug'];
    $name = $request->get_param('name');
    Ada_Aba::log(sprintf('%1$s: slug: %2$s, name: %3$s', __FUNCTION__, $slug, $name));
    error_log(print_r($request->get_params(), true));

    $course = Ada_Aba_Course::get_by_slug($slug);
    if ($course) {
      $course->setName($name);
      $course->update();
    }

    return rest_ensure_response(new Ada_Aba_Course_Scalar($course));
  }

  public function get_products()
  {
    if (!current_user_can('manage_options')) {
      return new WP_Error('rest_forbidden', esc_html__('You do not have permissions to access this resource.', 'my-text-domain'), array('status' => 401));
    }

    // In practice this function would fetch the desired data. Here we are just making stuff up.
    $products = array(
      '1' => 'I am product 1',
      '2' => 'I am product 2',
      '3' => 'I am product 3',
    );

    return rest_ensure_response($products);
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

  private function get_courses_fragment(
    $course,
  ) {
    ob_start();
    include 'partials/ada-aba-admin-courses-course-fragment.php';
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

