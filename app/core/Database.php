<?php

class Database {

    private $dbcon = null;
    private $stmt = null;
    private static $database = null;

    public function __construct() {
        // Get config values first
        $host = Config::get('database/db_host');
        $dbname = Config::get('database/db_name');
        $user = Config::get('database/db_user');
        $pass = Config::get('database/db_pass');
        
        // Verify these values are strings
        if (is_array($host) || is_array($dbname) || is_array($user) || is_array($pass)) {
            throw new Exception('Database configuration values must be strings, not arrays');
        }
        
        $dsn = "pgsql:host={$host};dbname={$dbname}";
        
        try {
            $this->dbcon = new PDO($dsn, $user, $pass);
            $this->dbcon->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbcon->exec("SET NAMES 'utf8'");
            $this->dbcon->exec("SET TIME ZONE 'Asia/Manila'");
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public static function open_db() {
        if (self::$database === null) {
            self::$database = new Database();
        }
        return self::$database;
    }


    public function prepare($query) {
        $this->stmt = $this->dbcon->prepare($query);
        return $this->stmt; // ✅ now it returns the PDOStatement
    }

    public function query($sql) {
        $this->stmt = $this->dbcon->prepare($sql);
    }


    public function bindValue($param, $value) {
        $type = self::getPDOtype($value);
        $this->stmt->bindValue($param, $value, $type);
    }

    public function bindParam($param, $var) {
        $type = self::getPDOtype($var);
        $this->stmt->bindValue($param, $var, $type);
    }

    public function execute($arr = null) {
        if ($arr === null) {
            return $this->stmt->execute();
        } else {
            return $this->stmt->execute($arr);
        }
    }

    public function fetchColumn() {
        return $this->stmt->fetchColumn(); // instead of fetchAll()
    }

    public function fetchAllAssociative() {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchAssociative() {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    private static function getPDOtype($value) {
        switch (true) {
            case is_int($value):
                return PDO::PARAM_INT;
            case is_bool($value):
                return PDO::PARAM_BOOL;
            case is_null($value):
                return PDO::PARAM_NULL;
            default:
                return PDO::PARAM_STR;
        }
    }

    public function fetchSingle() {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchObject() {
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function countRows() {
        return $this->stmt->rowCount();
    }

    public function lastInsertedId() {
        return $this->dbcon->lastInsertId();
    }

    public function beginTransaction() {
        return $this->dbcon->beginTransaction();
    }

    public function endTransaction() {
        return $this->dbcon->commit();
    }

    public function cancelTransaction() {
        return $this->dbh->rollBack();
    }

    public function debugDumpParams() {
        return $this->stmt->debugDumpParams();
    }

    public function countAll($table) {
        $this->stmt = $this->dbcon->prepare('SELECT COUNT(*) as count FROM ' . $table);
        $this->execute();
        return (int)$this->fetchAssociative()["count"];
    }

    public function getAll($table) {
        $this->stmt = $this->dbcon->prepare('SELECT * FROM '.$table);
        $this->execute();
    }

    public function deleteAll($table) {
        $this->stmt = $this->dbcon->prepare('DELETE FROM '.$table);
        $this->execute();
    }

    public function deleteById($table, $id) {
        $this->stmt = $this->dbcon->prepare('DELETE FROM ' .$table. ' WHERE id = :id LIMIT 1');
        $this->bindValue(':id', $id);
        $this->execute();
    }

    public function getById($table, $id) {
        $this->stmt = $this->dbcon->prepare('SELECT * FROM ' .$table. ' WHERE id = :id LIMIT 1');
        $this->bindValue(':id', $id);
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByEmail($table, $email) {
        $this->stmt = $this->dbcon->prepare('SELECT * FROM ' .$table. ' WHERE email = :email LIMIT 1');
        $this->bindValue(':email', $email);
        $this->execute();
    }

    public function getByUserId($table, $user_id) {
        $this->stmt = $this->dbcon->prepare('SELECT * FROM ' .$table. ' WHERE user_id = :user_id');
        $this->bindValue(':user_id', $user_id);
        $this->execute();
    }

    public function getByUserEmail($table, $user_email) {
        $this->stmt = $this->dbcon->prepare('SELECT * FROM ' .$table. ' WHERE user_email = :user_email');
        $this->bindValue(':user_email', $user_email);
        $this->execute();
    }

    public static function close_db() {
        if (isset(self::$database)) {
            self::$database->dbcon = null;
            self::$database->stmt = null;
            self::$database = null;
        }
    }

}