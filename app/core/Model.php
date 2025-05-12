<?php

class Model {
    protected $db;

    public function __construct() {
        $this->db = Database::open_db();  // initializes your custom DB wrapper
    }
}
