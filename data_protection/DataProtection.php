<?php namespace feedthemsocial\data_protection;

use feedthemsocial\includes\DebugLog;
/**
 */

// Exit if accessed directly
if ( ! \defined( 'ABSPATH' ) ){
    exit;
}

/**
 * Class responsible for encrypting and decrypting data.
 *
 * @since 1.0.0
 * @access private
 * @ignore
 */
class DataProtection {

    /**
     * Key to use for encryption.
     *
     * @since 1.0.0
     * @var string
     */
    private $key;

    /**
     * Salt to use for encryption.
     *
     * @since 1.0.0
     * @var string
     */
    private $salt;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->key  = $this->getDefaultKey();
        $this->salt = $this->getDefaultSalt();
    }

    /**
     * Encrypt.
     *
     * Encrypt the data.
     *
     * @param $value
     * @return string
     */
    public function encrypt($value) {

        DebugLog::log( 'dataProtection', 'Decoded JSON Value', $value );

        if ( ! \extension_loaded( 'openssl' ) || empty( $value ) ) {
            return false;
        }

        $method = 'aes-256-ctr';
        $ivlen  = openssl_cipher_iv_length( $method );
        $iv     = openssl_random_pseudo_bytes( $ivlen );

        if( \is_object($value) ) {
            $value =  json_encode($value);
        }

        $encrypted_value = openssl_encrypt( $value . $this->salt, $method, $this->key, 0, $iv );
        if ( ! $encrypted_value ) {
            return false;
        }

        DebugLog::log( 'dataProtection', 'Encrypted Value', $encrypted_value );

        return base64_encode( $iv . $encrypted_value );
    }
    /**
     * Decrypt.
     *
     * Decrypt the data.
     *
     * @param $encrypted_value
     * @return string
     */
    public function decrypt( $encrypted_value ) {
        if ( ! \extension_loaded( 'openssl' ) || empty( $encrypted_value ) ) {
            return false;
        }
        $encrypted_value = base64_decode( $encrypted_value, true );

        $method = 'aes-256-ctr';
        $ivlen  = openssl_cipher_iv_length( $method );
        $iv     = substr( $encrypted_value, 0, $ivlen );


        $encrypted_value = substr( $encrypted_value, $ivlen );

        $decrypted_value = openssl_decrypt( $encrypted_value, $method, $this->key, 0, $iv );
        if ( ! $decrypted_value || substr( $decrypted_value, - \strlen( $this->salt ) ) !== $this->salt ) {
            return false;
        }

        return substr( $decrypted_value, 0, - \strlen( $this->salt ) );
    }

    /**
     * Gets the default encryption key to use.
     *
     * @since 1.0.0
     *
     * @return string Default (not user-based) encryption key.
     */
    private function getDefaultKey(): string
    {
        if ( \defined( 'AUTH_KEY' ) && '' !== AUTH_KEY ) {

            DebugLog::log('dataProtection', 'WordPress Auth Key', AUTH_KEY);

            return AUTH_KEY;


        }
        return 'das-ist-kein-geheimer-schluessel';
    }

    /**
     * Gets the default encryption salt to use.
     *
     * @since 1.0.0
     *
     * @return string Encryption salt.
     */
    private function getDefaultSalt(): string
    {
        if ( \defined( 'AUTH_KEY' ) && '' !== AUTH_KEY ) {
            return AUTH_KEY;
        }

        // If this is reached, you're either not on a live site or have a serious security issue.
        return 'das-ist-kein-geheimes-salz';
    }
}
