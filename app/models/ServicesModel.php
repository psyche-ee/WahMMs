<?php

class ServicesModel extends Model {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllServices() {
        $stmt = $this->db->prepare("SELECT * FROM services ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getServiceById($id) {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}