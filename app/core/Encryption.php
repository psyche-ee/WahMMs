<?php

class Encryption {

    const CIPHER = 'aes-256-cbc';

    const HASH_FUNCTION = 'sha256';

    private function __construct() {}

    public static function encryptId($id) {
        return self::alphaID($id, false, 3);
    }

    public static function decryptIdWithDash($id) {

        if (empty($id)) {
            throw new Exception("the id to decrypt can't be empty");
        }

        $decryptId = 0;
        $chars = self::getCharacters();
        $base = strlen($chars);
        $id = explode("-", $id)[1];

        $len = strlen($id) -1;

        for ($t = $len; $t >= 0; $t--) {
            $bcp = bcpow($base, $len - $t);
            $decryptId = $decryptId + strpos($chars, substr($id, $t, 1)) * (int)$bcp;
        }
        return ((int)$decryptId - 1142) / 9518436;
    }

    private static function getCharacters() {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        $i = [];
        for ($n = 0; $n < strlen($chars); $n++) {
            $i[] = substr($chars, $n, 1);
        }

        $key_hash = hash('sha256', Config::get('encryption/hash_key'));
        $key_hash = (strlen($key_hash) < strlen($chars) ? hash('sha512', Config::get('encryption/hash_key')) : $key_hash);

        for ($n = 0; $n < strlen($chars); $n++) {
            $p[] = substr($key_hash, $n, 1);
        }

        array_multisort($p, SORT_DESC, $i);
        $chars = implode($i);

        return $chars;
    }

    public static function encrypt($plain) {
        $iv_size = openssl_cipher_iv_length(self::CIPHER);
        $iv = openssl_random_pseudo_bytes($iv_size);
    
        $key = mb_substr(
            hash(self::HASH_FUNCTION, Config::get('encryption/encryption_key') . Config::get('encryption/hmac_salt')),
            0, 32, '8bit'
        );
    
        $encrypted_string = openssl_encrypt($plain, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
    
        $ciphertext = $iv . $encrypted_string;
    
        $hmac = hash_hmac('sha256', $ciphertext, $key, true); // binary output
    
        $final = $hmac . $ciphertext;
    
        return base64_encode($final); // encode for URL/email safety
    }
    
    public static function encrypt_id($id) {
        // Example using base64 encoding (NOT secure for real-world use)
        return base64_encode($id);
    }

    public static function decrypt_id($encoded) {
        return base64_decode($encoded);
    }

    public static function decryptId($encryptedId) {
        $decoded = base64_decode($encryptedId);
        if ($decoded === false) {
            throw new Exception('Invalid base64 encoding in encrypted ID.');
        }
    
        $key = mb_substr(
            hash(self::HASH_FUNCTION, Config::get('encryption/encryption_key') . Config::get('encryption/hmac_salt')),
            0, 32, '8bit'
        );
    
        $hmac_length = 32; // 256 bits / 8 = 32 bytes
        $iv_length = openssl_cipher_iv_length(self::CIPHER);
    
        $hmac = substr($decoded, 0, $hmac_length);
        $iv = substr($decoded, $hmac_length, $iv_length);
        $encrypted_data = substr($decoded, $hmac_length + $iv_length);
    
        $calculated_hmac = hash_hmac('sha256', $iv . $encrypted_data, $key, true);
    
        if (!hash_equals($hmac, $calculated_hmac)) {
            throw new Exception('HMAC verification failed.');
        }
    
        $decrypted = openssl_decrypt($encrypted_data, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new Exception('Decryption failed.');
        }
    
        return $decrypted;
    }
    
    public static function decrypt($ciphertext) {
        $ciphertext = base64_decode($ciphertext); // ✅ DECODE HERE
    
        if ($ciphertext === false) {
            throw new Exception("Base64 decoding failed.");
        }
    
        $key = mb_substr(
            hash(self::HASH_FUNCTION, Config::get('encryption/encryption_key') . Config::get('encryption/hmac_salt')),
            0, 32, '8bit'
        );
    
        $hmac = substr($ciphertext, 0, 32); // Binary HMAC is 32 bytes
        $iv_ciphertext = substr($ciphertext, 32);
    
        $calculated_hmac = hash_hmac('sha256', $iv_ciphertext, $key, true); // binary output
    
        if (!hash_equals($hmac, $calculated_hmac)) {
            throw new Exception("Data integrity check failed.");
        }
    
        $iv_size = openssl_cipher_iv_length(self::CIPHER);
        $iv = substr($iv_ciphertext, 0, $iv_size);
        $encrypted = substr($iv_ciphertext, $iv_size);
    
        $decrypted = openssl_decrypt($encrypted, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        if ($decrypted === false) {
            throw new Exception("Decryption failed.");
        }
    
        return $decrypted;
    }
    
    private static function alphaID($input, $to_num = false, $pad_up = false, $pass_key = null) {
        $index = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $base = strlen($index);
    
        if ($to_num) {
            // Decode
            $input = strrev($input);
            $out = 0;
            $len = strlen($input) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = bcpow($base, $len - $t);
                $out = $out + strpos($index, substr($input, $t, 1)) * $bcpow;
            }
    
            if (is_numeric($pad_up)) {
                $out -= pow($base, $pad_up);
            }
            return (int)$out;
        } else {
            // Encode
            if (is_numeric($pad_up)) {
                $input += pow($base, $pad_up);
            }
    
            $out = "";
            while ($input > 0) {
                $out = $index[$input % $base] . $out;
                $input = floor($input / $base);
            }
            return strrev($out);
        }
    }
}