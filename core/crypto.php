<?php


class crypto
{
    private const ITERATION_COUNT = 1000;
    private const KEY_LENGTH = 256;
    private const PBKDF2_DERIVATION_ALGORITHM = "sha1";
    private const CIPHER_ALGORITHM = "aes-256-cbc";
    private const PKCS5_SALT_LENGTH = 32;
    private const DELIMITER = ".";
    public static function encryptThis($plaintext): false|string
    {
        $salt = random_bytes(self::PKCS5_SALT_LENGTH);
        $key = self::deriveKey($salt);
        try {
            $ivLength = openssl_cipher_iv_length(self::CIPHER_ALGORITHM);
            $iv = random_bytes($ivLength);
            $encrypted = openssl_encrypt($plaintext, self::CIPHER_ALGORITHM, $key, OPENSSL_RAW_DATA, $iv);
            $saltEncoded = self::base64UrlEncode($salt);
            $ivEncoded = self::base64UrlEncode($iv);
            $encryptedEncoded = self::base64UrlEncode($encrypted);
            return $saltEncoded . self::DELIMITER . $ivEncoded . self::DELIMITER . $encryptedEncoded;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function decryptThis($ciphertext): false|string
    {
        try {
            if(strlen($ciphertext) > 20){
                $parts = explode(self::DELIMITER, $ciphertext);
                if (count($parts) !== 3) {
                    throw new Exception("Invalid ciphertext format.");
                }
                list($saltEncoded, $ivEncoded, $encryptedEncoded) = $parts;
                // Decode Base64 URL-Safe
                $salt = self::base64UrlDecode($saltEncoded);
                $iv = self::base64UrlDecode($ivEncoded);
                $encrypted = self::base64UrlDecode($encryptedEncoded);
                $key = self::deriveKey($salt);
                return openssl_decrypt($encrypted, self::CIPHER_ALGORITHM, $key, OPENSSL_RAW_DATA, $iv);
            }else{
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    private static function deriveKey($salt): false|string
    {
        return openssl_pbkdf2(PASSWORD_CRYPTO, $salt, self::KEY_LENGTH / 8, self::ITERATION_COUNT, self::PBKDF2_DERIVATION_ALGORITHM);
    }
    private static function base64UrlEncode($data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    private static function base64UrlDecode($data): false|string
    {
        if ($data === null) return '';
        $base64 = strtr($data, '-_', '+/');
        return base64_decode($base64);
    }
}
