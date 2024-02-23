<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.adadevelopersacademy.org
 * @since      1.0.0
 *
 * @package    Ada_Aba
 * @subpackage Ada_Aba/public
 */

use Models\Ada_Aba_Learner;

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
class Ada_Aba_Public
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

  public static $registration_shortcode = "registration-form";
  public static $confirm_shortcode = "confirm";

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
  }

  private function get_shortcode($bare_shortcode)
  {
    return $this->plugin_name . '-' . $bare_shortcode;
  }

  private function get_confirm_page()
  {
    $options = Ada_Aba_Options::get_options($this->plugin_name);
    $post_id = $options->get_confirmation_page();
    $post = get_post($post_id); 
    $slug = $post?->post_name;
    return $slug;
  }

  private function get_progress_page($user)
  {
    $options = Ada_Aba_Options::get_options($this->plugin_name);
    $post_id = $options->get_registered_page();
    $post = get_post($post_id); 

    if ($post) {
      $slug = $post->post_name;
      return $slug . "?u=$user";
    }

    return null;
  }

  private function register_load_handlers()
  {
    $registration = $this->get_shortcode(self::$registration_shortcode);
    $confirm_page = $this->get_confirm_page();

    if (!$confirm_page) {
      return;
    }

    $this->load_handlers = array(
      array(
        'method' => array($this, 'is_in_post'), 
        'value' => $registration, 
        'handler' => array($this, 'handle_registration_form'),
      ),
      array(
        'method' => array($this, 'is_page_name'), 
        'value' => $confirm_page, 
        'handler' => array($this, 'handle_confirm'),
      ),
    );
  }

  private function is_in_post($value)
  {
    return isset($_POST[$value]);
  }

  private static function extract_page_name($url)
  {
    // strip off any query params starting from ? to the end
    // then strip any trailing slashes
    // then remove the leading slash and host info
    $url = preg_replace('/\?.*$/', '', $url);
    $url = rtrim($url, '/');
    $url = preg_replace('/^.*\//', '', $url);
    return $url;
  }

  private function is_page_name($value)
  {
    $url = $_SERVER['REQUEST_URI'];
    $url = self::extract_page_name($url);

    // return whether what's left matches the value
    $matches = ( $url === $value );
    Ada_Aba::log(sprintf(
      '%1$s: url: [%2$s], value: [%3$s], matches: %4$d',
      __FUNCTION__,
      $url,
      $value,
      $matches
    ));
    return $matches;
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
     * defined in Ada_Aba_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Ada_Aba_Loader will then create the relationship
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
     * defined in Ada_Aba_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Ada_Aba_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ada-aba-public.js', array('jquery'), $this->version, false);
  }

  private function get_current_page()
  {
    global $wp;
    // return home_url($wp->request);
    return $wp->request;
    // return get_permalink();
    // global $post;
    // return $post->post_name;
  }

  private function get_post_value($key)
  {
    return isset($_POST[$key]) ? $_POST[$key] : '';
  }

  // the function parameters are used within the template
  private function get_registration_form_content(
    $form_url,
    $verify_link,
    $shortcode,
    $first_name,
    $last_name,
    $email,
    $error_message,
  ) {
    ob_start();
    include 'partials/ada-aba-public-registration-form.php';
    return ob_get_clean();
  }

  public function shortcode_register_form()
  {
    if ($this->did_registration_post()) {
      return $this->handle_registration_post();
    } else if ($this->is_resend_request()) {
      return $this->handle_resend_request();
    } else {
      return $this->show_registration_form();
    }
  }

  private function is_user_confirmed()
  {
    return isset($_GET['u']);
  }

  private function get_confirmed_user()
  {
    return $_GET['u'];
  }

  public function shortcode_confirm()
  {
    if ($this->is_user_confirmed()) {
      return $this->handle_confirm_success();
    }
    else {
      return $this->handle_confirm_failed();
    }
  }

  public function handle_confirm()
  {
    $this->clean_expired_registrations();

    $verify_code = $this->get_verify_code();
    Ada_Aba::log(sprintf(
      '%1$s: verify_code: %2$s',
      __FUNCTION__,
      $verify_code
    ));

    if (empty($verify_code)) {
      // let the request fall through to actually rendering the page, which
      // should encounter our shortcode
      return;
    }

    $confirm_page = $this->get_confirm_page();
    if (empty($confirm_page)) {
      return;
    }

    // error_log('attempt to verify');
    $learner = Models\Ada_Aba_Learner::get_by_verify_code($verify_code);
    $failed = false;

    if ($learner) {
      try {
        $learner->verify();
      } catch (Ada_Aba_Exception $e) {
        $failed = true;
      }
    }
    
    if ($failed || empty($learner)) {
      // return a failure to confirm page
      wp_redirect(home_url($confirm_page));
      return;
    } else {
      // return a success to confirm page
      $user = $learner->getSlug();
      $this->send_registered_email($learner);
      wp_redirect(home_url($confirm_page) . "?u=$user");
      return;
    }

    exit;
  }

  private function get_registered_content(
    $progress_link,
  ) {
    ob_start();
    include 'partials/ada-aba-public-registered-page.php';
    return ob_get_clean();
  }

  private function get_registered_error_content() {
    ob_start();
    include 'partials/ada-aba-public-registered-error.php';
    return ob_get_clean();
  }

  private function get_verify_code() {
    return isset($_GET['verify']) ? $_GET['verify'] : '';
  }

  private function handle_resend_request()
  {
    $this->clean_expired_registrations();

    $email_raw = $_GET['resend'];
    $email = urldecode($email_raw);
    $resend_link = home_url($this->get_current_page()) . "?resend=$email";
    Ada_Aba::log(sprintf(
      '%1$s: email: %2$s',
      __FUNCTION__,
      $email_raw
    ));

    $learner = Models\Ada_Aba_Learner::get_by_email($email_raw);
    if ($learner) {
      $this->send_registration_email($learner);
    }

    return $this->get_registration_resend_content( $resend_link );
  }

  private function handle_registration_post()
  {
    $email = urlencode(self::get_post_value('email'));
    $resend_link = home_url($this->get_current_page()) . "?resend=$email";

    return $this->get_registration_posted_content( $resend_link );
  }

  private function handle_confirm_success() {
    $user = $this->get_confirmed_user();
    $progress_link = home_url($this->get_progress_page($user)) ?? '';
    return $this->get_registered_content($progress_link);
  }

  private function handle_confirm_failed()
  {
    return $this->get_registered_error_content();
  }

  private function show_registration_form()
  {
    $form_url = $this->get_current_page();
    return $this->get_registration_form_content(
      $form_url,
      home_url($form_url),
      $this->get_shortcode(self::$registration_shortcode),
      self::get_post_value('first_name'),
      self::get_post_value('last_name'),
      self::get_post_value('email'),
      self::get_post_value('error'),
    );
  }

  private function did_registration_post()
  {
    $opt = $this->get_shortcode(self::$registration_shortcode);
    return isset($_POST[$opt]);
  }

  private function is_resend_request()
  {
    return isset($_GET['resend']);
  }

  private function plugin_will_handle() {
    $options = Ada_Aba_Options::get_options($this->plugin_name);
    Ada_Aba_Session::start($this->plugin_name, $options->get_private_key());
    // if (null === $session->get('handled')) {
    //   $session->set('handled', true);
    // }

    // $handled = ! $session->get('handled');
    // $session->set('handled', $handled);

    // Ada_Aba::log(sprintf('%1$s: %2$d', __FUNCTION__, $session->get('handled')));
    // $session->save();
  }

  public function handle_page_loaded()
  {
    global $wp;
    // $current_url = home_url(add_query_arg(array(), $wp->request));
    // $current_url = add_query_arg( $wp->query_vars, home_url() );
    // $current_url = $_SERVER['REQUEST_URI'];
    // $request_method = $_SERVER['REQUEST_METHOD'];

    // Ada_Aba::log(sprintf('%1$s: url: %2$s verb: %5$s _POST: %3$s _GET: %4$s',
    //   __FUNCTION__, $current_url,
    //   print_r($_POST, true), print_r($_GET, true), $request_method));

    foreach ($this->load_handlers as $idx => $entry) {
      $method = $entry['method'];
      $value = $entry['value'];
      $handler = $entry['handler'];

      if (call_user_func($method, $value)) {
        $this->plugin_will_handle();
        error_log("$idx succeeded"); 
        call_user_func($handler);
      } else {
        error_log("$idx failed"); 
      }
    }

    Ada_Aba_Session::close();
  }

  private function get_registration_posted_content(
    $resend_link,
  ) {
    ob_start();
    include 'partials/ada-aba-public-registration-posted.php';
    return ob_get_clean();
  }

  private function get_registration_resend_content(
    $resend_link,
  ) {
    ob_start();
    include 'partials/ada-aba-public-registration-resend.php';
    return ob_get_clean();
  }

  // the function parameters are used within the template
  private function get_registration_email_content(
    $first_name,
    $last_name,
    $email,
    $verify_link,
  ) {
    ob_start();
    include 'partials/ada-aba-public-registration-email.php';
    return ob_get_clean();
  }

  // the function parameters are used within the template
  private function get_registered_email_content(
    $first_name,
    $last_name,
    $email,
    $progress_link,
  ) {
    ob_start();
    include 'partials/ada-aba-public-registered-email.php';
    return ob_get_clean();
  }

  private function lookup_verify_link()
  {
    $options = Ada_Aba_Options::get_options($this->plugin_name);

    $page_id = (int) $options->get_confirmation_page();
    if ($page_id <= 0) {
      return $_POST['verify_link'];
    }

    return get_page_link($page_id);
  }

  private function clean_expired_registrations() {
      Models\Ada_Aba_Learner::clean_expired_registrations();
  }

  private function handle_registration_form()
  {
    $this->clean_expired_registrations();

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
      $_POST['error']  = 'Please provide valid values for all inputs.';
      return;
    }

    Ada_Aba::log(sprintf(
      '%1$s: first: %2$s, last: %3$s, email: %4$s',
      __FUNCTION__,
      $first_name,
      $last_name,
      $email
    ));

    $learner = Models\Ada_Aba_Learner::create(
      $first_name,
      $last_name,
      $email,
    );

    try {
      $learner->insert();
    } catch (Ada_Aba_Exception $e) {
      // potentially add code to let user update or resend email
    }

    // for now, always send the email
    $this->send_registration_email($learner);
  }

  private function send_registration_email($learner) {
    $options = Ada_Aba_Options::get_options($this->plugin_name);

    $first_name = $learner->getFirstName();
    $last_name = $learner->getLastName();
    $email = $learner->getEmail();
    $verify_link = $this->lookup_verify_link();
    Ada_Aba::log(sprintf(
      '%1$s: first: %2$s, last: %3$s, email: %4$s, verify_link: %5$s',
      __FUNCTION__,
      $first_name,
      $last_name,
      $email,
      $verify_link
    ));
    
    $challenge = $learner->getChallengeNonce();

    $message = $this->get_registration_email_content(
      $first_name,
      $last_name,
      $email,
      "$verify_link?verify=$challenge",
    );
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if ($options->get_send_email()) {
      wp_mail($email, 'Ada Build Registration', $message, $headers);
    }
  }

  private function send_registered_email($learner) {
    $options = Ada_Aba_Options::get_options($this->plugin_name);

    $first_name = $learner->getFirstName();
    $last_name = $learner->getLastName();
    $email = $learner->getEmail();
    $verify_link = $this->lookup_verify_link();
    Ada_Aba::log(sprintf(
      '%1$s: first: %2$s, last: %3$s, email: %4$s, verify_link: %5$s',
      __FUNCTION__,
      $first_name,
      $last_name,
      $email,
      $verify_link
    ));
    
    $progress_link = home_url($this->get_progress_page($learner->getSlug()));

    $message = $this->get_registered_email_content(
      $first_name,
      $last_name,
      $email,
      $progress_link,
    );
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if ($options->get_send_email()) {
      wp_mail($email, 'Ada Build Confirmed', $message, $headers);
    }
  }
}
