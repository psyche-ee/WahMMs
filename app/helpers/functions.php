<?php

/**
 * =================================================================
 *  HOLD OR DISPLAY OLD CHECKED VALUES AFTER PAGE REFRESH
 * =================================================================
 */

function old_checked (string $key, string $value, string $default = ""):string {

    if (isset($_POST[$key])) {
        if ($_POST[$key] == $value) {
            return ' checked ';
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && $default == $value) {
            return ' checked ';
        }
    }

    return '';
}

/**
 * =================================================================
 *  HOLD OR DISPLAY OLD INPUT VALUES AFTER PAGE REFRESH
 * =================================================================
 */

function old_value (string $key, mixed $default = "", string $mode = 'post'):mixed {
    $POST = ($mode == 'post') ? $_POST : $_GET;
    if (isset($POST[$key])) {
        return $POST[$key];
    }

    return $default;
}

/**
 * =================================================================
 *  HOLD OR DISPLAY OLD SELECTED VALUES AFTER PAGE REFRESH
 * =================================================================
 */

function old_select (string $key, mixed $value, mixed $default = "", string $mode = 'post'):mixed {
    $POST = ($mode == 'post') ? $_POST : $_GET;
    if (isset($POST[$key])) {
        if ($POST[$key] == $value) {
            return " selected ";
        }
    } else if ($default == $value) {
        return " selected ";
    }

    return "";
    
}