<?php

class Redirect {

    public function to($location) {
        if (filter_var($location, FILTER_VALIDATE_URL)) {
            header("Location: $location");
        } else {
            header("Location: " . rtrim(baseurl(), '/') . '/' . ltrim($location, '/'));
        }
        exit;
    }

    public function back() {
        header("Location:" .$_SERVER['HTTP_REFERER']);
    }
}