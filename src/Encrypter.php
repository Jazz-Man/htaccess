<?php

namespace JazzMan\Htaccess;

use RuntimeException;

/**
 * Class Encrypter
 *
 * @package JazzMan\Htaccess
 */
class Encrypter
{
    /**
     * @var string
     */
    private $key;

    /**
     * Create a new encrypter instance.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        if (!\extension_loaded('openssl')) {
            throw new RuntimeException('OpenSSL extension is not available.');
        }

        if (!\extension_loaded('mbstring')) {
            throw new RuntimeException('Multibyte String extension is not available.');
        }

        if (!$this->isValidKey($key)) {
            throw new RuntimeException('The encryption key length is not valid.');
        }

        $this->key = $key;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    private function isValidKey($key)
    {
        return \is_string($key) && 32 === mb_strlen($key, '8bit');
    }

    /**
     * @param mixed $value
     * @param bool  $serialize
     *
     * @return string
     * @throws \Exception
     */
    public function encrypt($value, $serialize = true)
    {
        $iv = random_bytes(16);
        // Encrypt the given value
        $encrypted = openssl_encrypt(
            $serialize ? serialize($value) : $value,
            'AES-256-CBC', $this->key, 0, $iv
        );

        if (false !== $encrypted) {
            // Create a keyed hash for the encrypted value
            $hmac = $this->hash($iv.$encrypted);

            return base64_encode($iv.$hmac.$encrypted);
        }
    }

    /**
     * Encrypt the given string without serialization.
     *
     * @param $value
     *
     * @return string
     * @throws \Exception
     */
    public function encryptString($value)
    {
        return $this->encrypt($value, false);
    }

    /**
     * Decrypt the given value.
     *
     * @param      $value
     * @param bool $unserialize
     *
     * @return mixed|string
     */
    public function decrypt($value, $unserialize = true)
    {
        $value = base64_decode($value);

        $iv = mb_substr($value, 0, 16, '8bit');
        $hmac = mb_substr($value, 16, 32, '8bit');
        $encrypted = mb_substr($value, 48, null, '8bit');

        // Create a keyed hash for the decrypted value
        $hmacNew = $this->hash($iv . $encrypted);

        if ($this->hashEquals($hmac, $hmacNew)) {
            // Decrypt the given value
            $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $this->key, 0, $iv);
            if ($decrypted !== false) {
                return $unserialize ? unserialize($decrypted) : $decrypted;
            }
        }
    }

    /**
     * Create a keyed hash for the given value.
     *
     * @param $value
     *
     * @return string
     */
    protected function hash($value)
    {
        return hash_hmac('sha256', $value, $this->key, true);
    }

    /**
     * @param $original
     * @param $new
     *
     * @return bool
     */
    protected function hashEquals($original, $new)
    {
        return hash_equals($original, $new);
    }
}
