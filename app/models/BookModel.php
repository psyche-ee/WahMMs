<?php

class BookModel extends Model {
    protected $db;

    public function __condtruct() {
        $this->db = new Database();
    }

    public function bookAppointment($userId, $serviceId, $serviceName, $date, $time) {
        $time = date("h:i A", strtotime($_POST["time"]));

        $stmt = $this->db->prepare("INSERT INTO appointments (user_id, service_id, appointment_date, appointment_time) VALUES (:user_id, :service_id, :appointment_date, :appointment_time)");
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':service_id', $serviceId);
        $stmt->bindValue(':appointment_date', $date);
        $stmt->bindValue(':appointment_time', $time);
        $stmt->execute();

        $user = $this->db->getById('users', $userId);
        $email = $user["email"];

        return Email::sendEmail(Config::get('mailer/email_booking_notification'), $email, ["name" => $user["name"]], ["date" => $date, 'time' => $time, 'service_name' => $serviceName]);
    }
}