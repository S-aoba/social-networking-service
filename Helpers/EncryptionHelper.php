<?php

namespace Helpers;
class EncryptionHelper
{
  private $cipher_method;
  private $key;

  public function __construct($key)
  {
    $this->cipher_method = 'aes-256-cbc';
    $this->key = $key;
  }

  public function encrypt($message)
  {
    $iv_length = openssl_cipher_iv_length($this->cipher_method);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($message, $this->cipher_method, $this->key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
  }

  public function decrypt($encryptedMessage)
  {
    list($encryptedData, $iv) = explode('::', base64_decode($encryptedMessage), 2);
    return openssl_decrypt($encryptedData, $this->cipher_method, $this->key, 0, $iv);
  }
}
