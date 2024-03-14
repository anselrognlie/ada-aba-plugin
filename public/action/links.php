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
    . (empty($user_slug) ? '' : '&' . Keys\USER . "=$user_slug");
}

function get_resend_link($slug)
{
  return Core::get_ada_build_url() . '?' . Keys\RESEND . "=$slug";
}

function get_confirm_link($action_slug)
{
  return Core::get_ada_build_url() . '?' . Keys\CONFIRM . "=$action_slug";
}

function get_error_link($error)
{
  $hex_err = dechex($error);
  return Core::get_ada_build_url() . '?' . Keys\ERROR . "=0x$hex_err";
}

function get_enroll_link($user_slug)
{
  return Core::get_ada_build_url() . '?' . Keys\ENROLL . "=$user_slug";
}

function get_complete_lesson_link($lesson_slug, $user_slug)
{
  return Core::get_ada_build_url() . '?'
    . Keys\COMPLETE . "=$lesson_slug&"
    . Keys\USER . "=$user_slug";
}

function get_request_certificate_link($enrollment_slug)
{
  return Core::get_ada_build_url() . '?' . Keys\REQUEST_CERTIFICATE . "=$enrollment_slug";
}

function get_certificate_link($completion_slug)
{
  return Core::get_ada_build_url() . '?' . Keys\CERTIFICATE . "=$completion_slug";
}

function redirect_to_confirm_page($action_slug, $halt = true)
{
  $url = get_confirm_link($action_slug);
  redirect_to_page($url, $halt);
}

function redirect_to_verify_page($nonce, $halt = true)
{
  $url = get_verify_link($nonce);
  redirect_to_page($url, $halt);
}

function redirect_to_confirmation_page($user_slug, $halt = true)
{
  $url = get_confirmation_link($user_slug);
  redirect_to_page($url, $halt);
}


function redirect_to_error_page($error, $halt = true)
{
  $url = get_error_link($error);
  redirect_to_page($url, $halt);
}

function redirect_to_progress_page($user_slug, $halt = true)
{
  $url = get_progress_link($user_slug);
  redirect_to_page($url, $halt);
}

function redirect_to_page($url, $halt = true)
{
  wp_redirect($url);

  if ($halt) {
    exit;
  }
}
