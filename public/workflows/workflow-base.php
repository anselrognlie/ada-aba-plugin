<?php

namespace Ada_Aba\Public\Workflows;

use Ada_Aba\Includes\Options;

abstract class Workflow_Base
{
  protected $plugin_name;
  protected $options;

  public function __construct($plugin_name)
  {
    $this->plugin_name = $plugin_name;
    $this->options = Options::get_options($this->plugin_name);
  }

  abstract public function can_handle_load_precise();
  abstract public function handle_load();
  abstract public function can_handle_page();
  abstract public function handle_page();

  public function can_handle_load()
  {
    $build_page = $this->get_ada_build_page();

    if (!$this->is_page_name($build_page)) {
      return false;
    }

    return $this->can_handle_load_precise();
  }

  protected function get_ada_build_page()
  {
    $post = get_post($this->options->get_ada_build_page());
    return $post ? $post->post_name : '@intentionally@illegal@page@name@';
  }

  protected function get_current_page()
  {
    global $wp;
    return $wp->request;
  }

  protected function get_current_url()
  {
    return home_url(add_query_arg(array(), $this->get_current_page()));
  }

  protected function is_in_post($value)
  {
    return isset($_POST[$value]);
  }

  protected function is_in_get($value)
  {
    return isset($_GET[$value]);
  }

  protected function is_page_name($value)
  {
    $url = $_SERVER['REQUEST_URI'];
    $url = self::extract_page_name($url);

    // return whether what's left matches the value
    $matches = ($url === $value);
    return $matches;
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

  protected function get_post_value($key)
  {
    return isset($_POST[$key]) ? $_POST[$key] : '';
  }
}
