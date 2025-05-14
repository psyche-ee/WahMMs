<?php

class ValidationRules {
    private $db;

    public function __construct($db = null) {
        // If no DB is passed in, initialize it here
        if ($db) {
            $this->db = $db;
        } else {
            // This assumes you have a Database class that returns a PDO object
            $this->db = Database::open_db(); // adjust to your actual DB connection method
        }
    }

    /**
     * =====================================================
     *                   VALIDATIONS
     * =====================================================
     */


    /**
     * Determine if an input field is required
     * 
     * @param array $value
     * @return bool
     */
    public static function isRequired($value) {
        if (filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
            return true;
        }
        return false;
    }

    public function isNumeric($value) {
        return is_numeric($value);
    }

    public function isPhoneNumber($value) {
        return preg_match('/^[0-9]{10,15}$/', $value); // Adjust regex as needed
    }

    public function isDate($value) {
        return (bool)strtotime($value);
    }

    /**
     * check if value is a valid email
     * 
     * @param string $email
     * @return bool
     */
    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * min string length
     * 
     * @param string $str
     * @param array $args(min)
     * 
     * @param bool
     */
    public static function minLen($str, $args) {
        return mb_strlen($str, 'UTF-8') >= (int)$args;
    }

    /**
     * max string length
     * 
     * @param string $str
     * @param array $args(max)
     * 
     * @return bool
     */
    public static function maxLen($str, $args) {
        return mb_strlen($str, 'UTF-8') <= (int)$args;
    }

    /**
     * check if value is a valid number
     * 
     * @param string|integer $value
     * @return bool
     */
    public static function integer($value) {
        return filter_var($value, FILTER_VALIDATE_INT);
    }

    /**
     * check if value is alphanumeric
     * 
     * @param mixed $value
     * @return bool
     */
    public static function alphaNum($value) {
        return preg_match('/^[a-zA-Z0-9]+$/', $value);
    }

    /**
     * check if password has atleast
     * - one lowercase letter
     * - one uppercase letter
     * - one number
     * - one special(non-word) character
     * 
     * @param mixed $value
     * @return bool
     */
    public static function password($value) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', $value);
    }

    /**
     * check if value is equal to another value(strings)
     * 
     * @param string $value
     * @param array $args(value)
     * @return bool
     */
    public function equal($value, $args) {
        return $value === $args[0];
    }

    /**
     * check if value is not equal to another value(strings)
     * 
     * @param string $value
     * @param array $args(value)
     * @return bool
     */
    public function notEqual($value, $args) {
        return $value !== $args[0];
    }

    /**
     * ==========================================
     *          Database Validations
     * ==========================================
     */

    /**
     * check if a value exists in a database table
     * 
     * @param string $table
     * @param string $column
     * @param mixed $value
     * @return bool
     */
    public function exists($table, $column, $value) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = :value");
        $stmt->execute(['value' => $value]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * check if a value is unique in a database table
     * 
     * @param string $table
     * @param string $column
     * @param mixed $value
     * @return bool
     */
    public function unique($value, $tableColumn) {
        list($table, $column) = $tableColumn;

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchColumn() == 0;
    }

    public function emailUnique($email) {
        $this->db = Database::open_db();
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            $timeElapsed = time() - $user['email_last_verification'];
            $expired = $timeElapsed >= 86400;
            if (empty($user["is_email_activated"]) && $expired) {
                // (new AuthModel())->resetEmailVerificationToken($user["id"], false);
                return true;
            }
            return false;
        }
        return true;
    }


    /**
     * ==========================================
     *              Login Validations
     * ==========================================
     */

    /**
     * check if input matches user's password
     * 
     * @param string $inputPassword
     * @param string $storedHash
     * @return bool
     */
    public static function verifyPassword($inputPassword, $storedHash) {
        return password_verify($inputPassword, $storedHash);
    }

    /**
     * check if username meets login pattern (alphanumeric, underscores allowed)
     * 
     * @param string $username
     * @return bool
     */
    public static function validUsername($username) {
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
    }


    /**
     * ==========================================
     *              File Validations
     * ==========================================
     */

    /**
     * check if file size is under a certain limit (in bytes)
     * 
     * @param int $fileSize
     * @param int $maxSize
     * @return bool
     */
    public static function maxFileSize($fileSize, $maxSize) {
        return $fileSize <= $maxSize;
    }

    /**
     * check if uploaded file is an allowed mime type
     * 
     * @param string $mimeType
     * @param array $allowedTypes
     * @return bool
     */
    public static function allowedMimeType($mimeType, $allowedTypes) {
        return in_array($mimeType, $allowedTypes);
    }

    public function credentials($args) {
        return isset($args['user_id'], $args['hashed_password'], $args['password']) &&
               password_verify($args['password'], $args['hashed_password']);
    }
    

}