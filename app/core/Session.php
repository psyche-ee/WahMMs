<?php

class Session {

    //constructor for Session object
    private function __construct() {

    }

    // starts session if not started yet
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function exists($name) {
        return isset($_SESSION[$name]);
    }

    //checks if session data exists and valid or not
    public static function isSessionValid($ip, $userAgent) {

        $isLoggedIn     = self::getIsLoggedIn();
        $userId         = self::getUserId();
        $userRole       = self::getUserRole();

        // 1. check if there is any data in session
        if (empty($isLoggedIn) || empty($userId) || empty($userRole)) {
            return false;
        }

        // 2. check ip addressand user agent
        if (!self::validateIPAddress($ip) || !self::validateUserAgent($userAgent)) {
            self::remove();
            return false;
        }

        // 3. check if session is expired
        if (!self::validateSessionExpiry()) {
            self::remove();
            return false;
        }

        return true;
    }

    public static function flash($name, $message = '') {
        if (!empty($message)) {
            // Set a flash message
            $_SESSION[$name] = $message;
        } elseif (isset($_SESSION[$name])) {
            // Retrieve and delete it
            $msg = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $msg;
        }
        return null;
    }

    // get isLoggedIn value(boolean)
    public static function getIsLoggedIn() {
        return empty($_SESSION["is_logged_in"]) || !is_bool($_SESSION["is_logged_in"]) ? false : $_SESSION["is_logged_in"];
    }

    // get user id
    public static function getUserId() {
        return empty($_SESSION["user_id"]) ? null : (int)$_SESSION["user_id"];
    }

    // get user role
    public static function getUserRole() {
        return empty($_SESSION["role"]) ? null : $_SESSION["role"];
    }

    // get csrf token
    public static function getCsrfToken() {
        return empty($_SESSION["csrf_token"]) ? null : $_SESSION["csrf_token"];
    }

    // get csrf token time
    public static function getCsrfTokenTime() {
        return empty($_SESSION["csrf_token_time"]) ? null : $_SESSION["csrf_token_time"];
    }

    // set session key and value
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    // get session value by key
    public static function get($key) {
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : null;
    }

    // get session value by key and destroy it
    public static function getAndDestroy($key) {
        if (array_key_exists($key, $_SESSION)) {

            $value = $_SESSION[$key];
            $_SESSION[$key] = null;
            unset($_SESSION[$key]);

            return $value;
        }
        return null;
    }

    // matches current ip address with the one stored in the session
    private static function validateIPAddress($ip) {

        if (!isset($_SESSION['ip']) || !isset($ip)) {
            return false;
        }

        return $_SESSION['ip'] === $ip; 
    }

    // matches current user agent with the one stored in the session
    private static function validateUserAgent($userAgent) {

        if (!isset($_SESSION['user_agent']) || !isset($userAgent)) {
            return false;
        }

        return $_SESSION['user_agent'] === $userAgent; 
    }

    // checks if session has been expired
    private static function validateSessionExpiry() {
        $max_time = 60 * 60 * 24; // 1day

        if (!isset($_SESSION['generated_time'])) {
            return false;
        }

        return ($_SESSION['generated_time'] + $max_time) > time();
    }

    //checks for session concurency
    public static function isConcurrentSessionExists() {

        $session_id = session_id();
        $userId = self::getUserId();
        
        if (isset($userId) && isset($session_id)) {
            $database = Database::open_db();
            $database->prepare("SELECT session_id FROM users WHERE id = :id LIMIT 1");

            $database->bindValue(':id', $userId);
            $database->execute();
            $result = $database->fetchAssociative();
            $user_SessionId = !empty($result) ? $result['session_id'] : null;

            return $session_id !== $user_SessionId;
        }
        return false;
    }

    // get csrf token and generate a new one if expired
    public static function generateCsrfToken() {

        $max_time = 60 * 60 * 24; // 1day
        $stored_time = self::getCsrfTokenTime();
        $csrf_token = self::getCsrfToken();

        if ($max_time + $stored_time <= time() || empty($csrf_token)) {
            $token = md5(uniqid(rand(), true));
            $_SESSION["csrf_token"] = $token;
            $_SESSION["csrf_token_time"] = time();
        }

        return self::getCsrfToken();
    }

    // reset session id, delete session file on server, and reassign the values
    public static function reset($data) {
        self::init(); // make sure session is started

        session_unset();
        session_destroy();

        session_start();
        session_regenerate_id(true);

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        $_SESSION['generated_time'] = time();
    }

    // remove all session data and destroy session
    public static function remove() {
        self::init(); // make sure session is started

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    // force logout by removing session and regenerating id
    public static function logout() {
        self::remove();
        session_start();
        session_regenerate_id(true);
    }

    // set initial session data when logging in
    public static function create($userId, $userName, $ip, $userAgent) {
        self::init(); // make sure session is started

        $_SESSION["is_logged_in"] = true;
        $_SESSION["user_id"] = (int) $userId;
        $_SESSION["name"] = $userName;
        $_SESSION["ip"] = $ip;
        $_SESSION["user_agent"] = $userAgent;
        $_SESSION["generated_time"] = time();
    }

    public static function destroy() {
        self::init();
        $_SESSION = [];
        session_destroy();
    }

}