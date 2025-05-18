<?php

class AdminModel extends Model {
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getHoursByDay($day) {
        $stmt = $this->db->prepare("SELECT * FROM business_hours WHERE day = :day LIMIT 1");
        $stmt->bindValue(':day', $day);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTodayHours() {
        date_default_timezone_set('Asia/Manila');
        $today = date('l');
        return $this->getHoursByDay($today);
    }

    public function isNowOpen() {
        date_default_timezone_set('Asia/Manila');
        $hours = $this->getTodayHours();
        $now = date('H:i:s');

        error_log("Current time: $now");
        error_log("Open time: {$hours['open_time']}");
        error_log("Close time: {$hours['close_time']}");
        error_log("Is open flag: {$hours['is_open']}");

        return $hours &&
            $hours['is_open'] &&
            $now >= $hours['open_time'] &&
            $now <= $hours['close_time'];
    }

    public function getAllAppointments() {
        $stmt = $this->db->prepare("SELECT 
            appointments.id,
            user_profiles.lastname,
            user_profiles.firstname,
            user_profiles.middlename,
            services.name,
            appointments.appointment_date,
            appointments.appointment_time,
            appointments.status
        FROM appointments
        JOIN user_profiles ON appointments.user_id = user_profiles.user_id
        JOIN services ON appointments.service_id = services.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalAppointments($status) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM appointments WHERE appointment_date = CURRENT_DATE AND status = :appointment_status ");
        $stmt->bindValue('appointment_status', $status);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getDailyAppointments() {
        $stmt = $this->db->prepare("SELECT 
            appointments.id,
            user_profiles.lastname,
            user_profiles.firstname,
            user_profiles.middlename,
            services.name,
            appointments.appointment_date,
            appointments.appointment_time,
            appointments.status
        FROM appointments
        JOIN user_profiles ON appointments.user_id = user_profiles.user_id
        JOIN services ON appointments.service_id = services.id
        WHERE appointment_date = CURRENT_DATE AND status = 'confirmed' ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    public function getSpecificAppointments($appointmentstatus) {
        $stmt = $this->db->prepare("SELECT 
            appointments.id,
            user_profiles.lastname,
            user_profiles.firstname,
            user_profiles.middlename,
            services.name,
            appointments.appointment_date,
            appointments.appointment_time,
            appointments.status
        FROM appointments
        JOIN user_profiles ON appointments.user_id = user_profiles.user_id
        JOIN services ON appointments.service_id = services.id
        WHERE appointments.status = :appointmentstatus ");
        $stmt->bindValue(':appointmentstatus', $appointmentstatus);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingAppointments() {
        return $this->getSpecificAppointments('pending');
    }

    public function getConfirmedAppointments() {
        return $this->getSpecificAppointments('confirmed');
    }

    public function getCancelledAppointments() {
        return $this->getSpecificAppointments('cancelled');
    }

    public function getCompletedAppointments() {
        return $this->getSpecificAppointments('completed');
    }

    public function addAction($id, $status) {
        $stmt = $this->db->prepare("UPDATE appointments SET status = :editstatus WHERE id = :id");
        $stmt->bindValue(':editstatus', $status);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function getAllPatients() {
        $stmt = $this->db->prepare("
            SELECT DISTINCT ON (user_profiles.user_id) 
                user_profiles.*, 
                users.email, 
                appointments.appointment_date,
                appointments.appointment_time
            FROM 
                user_profiles 
            JOIN appointments ON user_profiles.user_id = appointments.user_id 
            JOIN users ON user_profiles.user_id = users.id 
            WHERE 
                appointments.status = 'completed'
            ORDER BY 
                user_profiles.user_id, appointments.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookedTimesByDate($date) {
        $sql = "SELECT appointment_time FROM appointments WHERE appointment_date = :date";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':date', $date);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Return an array of booked times
    }

    public function getAnnouncements() {
        $stmt = $this->db->prepare("SELECT * FROM announcements");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addAnnouncement($title, $description, $type, $date_start, $date_end) {
        $stmt = $this->db->prepare("
            INSERT INTO announcements (title, description, type, date_start, date_end)
            VALUES (:title, :description, :type, :date_start, :date_end) ");
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':type', $type);
        $stmt->bindValue(':date_start', $date_start);
        $stmt->bindValue(':date_end', $date_end);

        return $stmt->execute();
    }


    public function deleteAnnouncement($id) {
        $stmt = $this->db->prepare("DELETE FROM announcements WHERE id = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function getServices() {
        $stmt = $this->db->prepare("SELECT * FROM services");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteService($id) {
        $stmt = $this->db->prepare("DELETE FROM services WHERE id = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function addService($name, $description, $long_description, $price, $category, $is_active = true) {
    $stmt = $this->db->prepare("
        INSERT INTO services (name, description, long_description, price, category, is_active)
        VALUES (:name, :description, :long_description, :price, :category, :is_active)
    ");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':long_description', $long_description);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':category', $category);
    $stmt->bindValue(':is_active', $is_active, PDO::PARAM_BOOL);

    return $stmt->execute();
}
}