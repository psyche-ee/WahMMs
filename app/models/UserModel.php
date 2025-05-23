<?php

class UserModel extends Model {

    // public function add($name, $email) {

    //     $query = "INSERT INTO users (name, email) VALUES (:name, :email)";

    //     $this->db->prepare($query);
    //     $this->db->bindValue(':name', $name);
    //     $this->db->bindValue(':email', $email);
    //     $this->db->execute();
    // }

    // public function getProfileInfo($userId) {
    //     $query = "SELECT id, 'name', email, created_at FROM users WHERE id = :id LIMIT 1";
    //     $this->db->prepare($query);
    //     $this->db->bindValue(':id', $userId);
    //     $this->db->execute();
    //     return $this->db->fetchAssociative(); // Or fetch(), depending on your DB wrapper
    // }

    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserProfile($userId) {
        $stmt = $this->db->prepare("SELECT * FROM user_profiles
                                    INNER JOIN users ON user_profiles.user_id = users.id
                                     WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

}  