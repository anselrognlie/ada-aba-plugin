<?php

namespace Ada_Aba\Includes\Security\Crypto;

// from https://stackoverflow.com/questions/48017856/correct-way-to-use-php-openssl-encrypt#answer-69072517

// --- Encrypt --- //
function encrypt($plaintext, $secret_key, $cipher = "AES-128-CBC")
{
  // regardless of key length, generate a 256-bit key
  $key = openssl_digest($secret_key, 'SHA256', true);

  $ivlen = openssl_cipher_iv_length($cipher);
  $iv = openssl_random_pseudo_bytes($ivlen);

  // binary cipher
  $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
  // or replace OPENSSL_RAW_DATA & $iv with 0 & bin2hex($iv) for hex cipher (eg. for transmission over internet)

  // or increase security with hashed cipher; (hex or base64 printable eg. for transmission over internet)
  $hmac = hash_hmac('sha256', $ciphertext_raw, $key, true);
  return base64_encode("$iv$hmac$ciphertext_raw");
}


// --- Decrypt --- //
// assumes ciphertext is base64 encoded and is structured as done in encrypt
function decrypt($ciphertext, $secret_key, $cipher = "AES-128-CBC")
{

  $c = base64_decode($ciphertext);

  $key = openssl_digest($secret_key, 'SHA256', TRUE);

  $ivlen = openssl_cipher_iv_length($cipher);

  $iv = substr($c, 0, $ivlen);
  $hmac = substr($c, $ivlen, $sha2len = 32);
  $ciphertext_raw = substr($c, $ivlen + $sha2len);
  $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);

  $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, true);
  if (hash_equals($hmac, $calcmac))
    return $original_plaintext;

  return null;
}
