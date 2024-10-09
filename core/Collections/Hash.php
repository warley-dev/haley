<?php

namespace Haley\Collections;

class Hash
{
    public static function createPassword(string $password)
    {
        $rash = password_hash($password, PASSWORD_DEFAULT);

        return $rash;
    }

    public static function checkPassword(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * @return string
     */
    public static function encrypt(string $value, string $key)
    {
        $ivlen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($value, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);

        return base64_encode($iv . $hmac . $ciphertext_raw);
    }

    /**
     * @return string|null
     */
    public static function decrypt(string $value, string $key)
    {
        $c = base64_decode($value);
        $ivlen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);

        if (hash_equals($hmac, $calcmac)) return $original_plaintext;

        return null;
    }
}
