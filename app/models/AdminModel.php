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
        WHERE appointment_date = CURRENT_DATE");
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
        $stmt = $this->db->prepare("SELECT * FROM user_profiles JOIN appointments on user_profiles.user_id = appointments.user_id JOIN users on user_profiles.user_id = users.id WHERE appointments.status = 'completed' ");
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    public function getBookedTimesByDate($date) {
        $sql = "SELECT appointment_time FROM appointments WHERE appointment_date = :date";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':date', $date);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Return an array of booked times
    }
}