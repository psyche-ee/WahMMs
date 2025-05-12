<?php

class Request {


    public $data = [];
    public $query = [];


    public function __construct() {
        
        $this->data     = $this->mergeData($_POST, $_FILES);
        $this->query    = $_GET;
    }

    private function mergeData(array $post, array $files) {
        foreach($post as $key => $value) {
            if (is_string($value)) { $post[$key] = trim($value); }
        }
        return array_merge($files, $post);
    }
    

    public function data($key) {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    public function query($key) {
        return array_key_exists($key, $this->query) ? $this->query[$key] : null;
    }

    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function clientIp() {
        // Check for standard IP address
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        // Check for proxy IP address
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // Default to REMOTE_ADDR if no other headers are found
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function userAgent() {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
}