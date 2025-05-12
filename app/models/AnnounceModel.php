<?php

class AnnounceModel extends Model {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllAnnouncements() {
        $stmt = $this->db->prepare("SELECT * FROM announcements WHERE date_start <= NOW() AND (date_end is NULL OR date_end >= NOW()) ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}