<?php

class Cookie {

    private static $userId = null;

    private static $token = null;

    private static $hashedCookie = null;

    public function __construct() {}

    public static function getUserId() {
        return (int)self::$userId;
    }

    public static function isCookieValid() {
        if (empty($_COOKIE['auth'])) {
            return false;
        }

        $cookie_auth = explode(':', $_COOKIE['auth']);
        if (count($cookie_auth) !== 3) {
            self::remove();
            return false;
        }

        list ($encryptedUserId, self::$token, self::$hashedCookie) = $cookie_auth;

        self::$userId = Encryption::decrypt($encryptedUserId);

        if (self::$hashedCookie === hash('sha256', self::$userId . ':' . self::$token . Config::get('cookie/cookie_secret_key')) && !empty(self::$token) && !empty(self::userId)) {

            $database = Database::open_db();
            $query = "SELECT id, cookie_token FROM users WHERE id = :id AND cookie_token = :cookie_token LIMIT 1";
            $database->prepare($query);
            $database->bindValue('id', self::$userId);
            $database->bindValue('cookie_token', self::$token);
            $database->execute();

            $isValid = $database->countRows() === 1 ? true : false;
        } else {
            $isValid = false;
        }

        if (!$isValid) {
            self::remove(self::$userId);
        }

        return $isValid;
    }

    public static function remove($userId = null) {

        if (!empty($userId)) {

            $database = Database::open_db();
            $query = "UPDATE users SET cookie_token = NULL WHERE id = :id";
            $database->prepare($query);
            $database->bindValue('id', $userId);
            $result = $database->execute();
        }

        self::$userId = self::$token = self::$hashedCookie = null;

        setcookie('auth', false, time() - (3600 * 3650), Config::get('cookie/cookie_path'), Config::get('cookie/cookie_domain'), Config::get('cookie/cookie_secure'), Config::get('cookie/cookie_http'));
    }

    public static function reset($userId) {

        self::$userId = $userId;
        self::$token = hash('sha256', mt_rand());
        $database = Database::open_db();

        $query = "UPDATE users SET cookie_token = :cookie_token WHERE id = :id";
        $database->prepare($query);

        $database->bindValue('cookie_token', self::$token);
        $database->bindValue('id', self::$userId);
        $result = $database->execute();

        $cookieFirstPart = Encryption::encrypt(self::$userId) . ':' . self::$token;

        self::$hashedCookie = hash('sha256', self::$userId . ':' . self::$token . Config::get('cookie/cookie_secret_key'));
        $authCookie = $cookieFirstPart . ':' . self::$hashedCookie;

        setcookie('auth', $authCookie, time() + Config::get('cookie/cookie_expiry'), Config::get('cookie/cookie_path'), Config::get('cookie/cookie_domain'), Config::get('cookie/cookie_secure'), Config::get('cookie/cookie_http'));
    }
}