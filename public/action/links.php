<?php

namespace Ada_Aba\Public\Action\Links;

use Ada_Aba\Includes\Core;
use Ada_Aba\Public\Action\Keys;

function get_verify_link($nonce)
{
  return Core::get_ada_build_url() . '?' . Keys\VERIFY . "=$nonce";
}

function get_progress_link($slug)
{
  return Core::get_ada_build_url() . "?u=$slug";
}

function get_confirmation_link($user_slug)
{
  return Core::get_ada_build_url() . '?' . Keys\CONFIRMATION
    . (empty($user_slug) ? '' : "&u=$user_slug");
}

function get_resend_link($slug)
{
  return Core::get_ada_build_url() . '?' . Keys\RESEND . "=$slug";
}

function get_confirm_link($action_slug)
{
  return Core::get_ada_build_url() . '?' . Keys\CONFIRM . "=$action_slug";
}

function redirect_to_confirm_page($action_slug, $halt = true)
{
  $url = get_confirm_link($action_slug);
  wp_redirect($url);

  if ($halt) {
    exit;
  }
}

function redirect_to_verify_page($nonce, $halt = true)
{
  $url = get_verify_link($nonce);
  wp_redirect($url);

  if ($halt) {
    exit;
  }
}

function redirect_to_confirmation_page($user_slug, $halt = true)
{
  $url = get_confirmation_link($user_slug);
  wp_redirect($url);

  if ($halt) {
    exit;
  }
}

