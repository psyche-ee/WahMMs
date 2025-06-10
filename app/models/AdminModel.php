<?php
date_default_timezone_set('Asia/Manila');
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
            TO_CHAR(appointments.appointment_time, 'HH12:MI AM') AS appointment_time,
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
            user_profiles.user_id,
            user_profiles.lastname,
            user_profiles.firstname,
            user_profiles.middlename,
            services.name,
            services.id AS service_id,
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
            TO_CHAR(appointments.appointment_time, 'HH12:MI AM') AS appointment_time,
            appointments.status,
            appointments.user_id
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

    // ---------------------------------------------------------------------------
    public function cancelPastConfirmedAppointments() {
        $stmt = $this->db->prepare("UPDATE appointments 
            SET status = 'cancelled' 
            WHERE status = 'confirmed' 
            AND appointment_date < CURRENT_DATE");
        $stmt->execute();
    }

    // public function cancelPastConfirmedAppointments() {
    //     $stmt = $this->db->prepare("
    //         UPDATE appointments 
    //         SET status = 'cancelled' 
    //         WHERE status = 'confirmed' 
    //         AND (appointment_date < CURRENT_DATE 
    //             OR (appointment_date = CURRENT_DATE AND appointment_time < CURRENT_TIME))
    //     ");
    //     $stmt->execute();
    // }

    //-----------------------------------------------------------------------------

    public function getCancelledAppointments() {
        return $this->getSpecificAppointments('cancelled');
    }

    public function getCompletedAppointments() {
        return $this->getSpecificAppointments('completed');
    }

    public function autoCancelUnattendedAppointments($date = null) {
        // Default to today if no date is provided
        if ($date === null) {
            $date = date('Y-m-d');
        }
        $stmt = $this->db->prepare("
            UPDATE appointments
            SET status = 'cancelled'
            WHERE status = 'confirmed'
            AND appointment_date <= :date
        ");
        $stmt->bindValue(':date', $date);
        return $stmt->execute();
    }

    public function addAction($id, $status) {
        $stmt = $this->db->prepare("UPDATE appointments SET status = :editstatus WHERE id = :id");
        $stmt->bindValue(':editstatus', $status);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Fetch user info for email
        $stmt = $this->db->prepare("
            SELECT a.appointment_date, a.appointment_time, u.email, u.name, s.name AS service_name
            FROM appointments a
            JOIN users u ON a.user_id = u.id
            LEFT JOIN services s ON a.service_id = s.id
            WHERE a.id = :id
        ");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return appointment & user info
    }


    public function getAllPatients() {
        $stmt = $this->db->prepare("
            SELECT 
                p.patient_id,
                u.id AS user_id,
                up.firstname,
                up.lastname,
                up.gender,
                up.date_of_birth,
                up.phone_number,
                u.email
            FROM patient p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN user_profiles up ON up.user_id = u.id
            ORDER BY up.lastname, up.firstname
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

    public function addService($name, $description, $long_description, $price, $category, $is_active = true, $imagePath = null) {
        $stmt = $this->db->prepare("
            INSERT INTO services (name, description, long_description, price, category, is_active, image_path)
            VALUES (:name, :description, :long_description, :price, :category, :is_active, :imagePath)
        ");
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':long_description', $long_description);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':category', $category);
        $stmt->bindValue(':is_active', $is_active, PDO::PARAM_BOOL);
        $stmt->bindValue(':imagePath', $imagePath);

        return $stmt->execute();
    }

    public function getBusinessHours() {
        $stmt = $this->db->prepare("
        SELECT * FROM business_hours 
        ORDER BY 
            CASE day
                WHEN 'Monday' THEN 1
                WHEN 'Tuesday' THEN 2
                WHEN 'Wednesday' THEN 3
                WHEN 'Thursday' THEN 4
                WHEN 'Friday' THEN 5
                WHEN 'Saturday' THEN 6
                WHEN 'Sunday' THEN 7
            END
    ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateBusinessHour($id, $open_time, $close_time, $is_open) {
        $stmt = $this->db->prepare("UPDATE business_hours SET open_time = :open_time, close_time = :close_time, is_open = :is_open WHERE id = :id");
        $stmt->bindValue('open_time', $open_time);
        $stmt->bindValue('close_time', $close_time);
        $stmt->bindValue('is_open', $is_open);
        $stmt->bindValue('id', $id);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertConfirmedPatients() {
        $stmt = $this->db->prepare("
            INSERT INTO patient (user_id)
            SELECT DISTINCT a.user_id
            FROM appointments a
            LEFT JOIN patient p ON a.user_id = p.user_id
            WHERE a.status = 'confirmed' AND p.user_id IS NULL
        ");

        return $stmt->execute();
    }

    // public function insertPatientIfCompleted($user_id) {
    //     // Check if user is already a patient
    //     $stmt = $this->db->prepare("SELECT COUNT(*) FROM patient WHERE user_id = :user_id");
    //     $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     if ($stmt->fetchColumn() > 0) {
    //         return false; // Already a patient
    //     }

    //     // Check if user has at least one completed appointment
    //     $stmt = $this->db->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = :user_id AND status = 'completed'");
    //     $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     if ($stmt->fetchColumn() == 0) {
    //         return false; // No completed appointment
    //     }

    //     // Insert as patient
    //     $stmt = $this->db->prepare("INSERT INTO patient (user_id) VALUES (:user_id)");
    //     $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    //     return $stmt->execute();
    // }

    public function insertNewDoctors() {
        // Select users with role 'doctor' who are not yet in doctor table
        $stmt = $this->db->prepare("
            SELECT id 
            FROM users 
            WHERE role = 'doctor' 
            AND id NOT IN (SELECT user_id FROM doctor)
        ");
        $stmt->execute();
        $doctors = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Insert each doctor into the doctor table
        $insertStmt = $this->db->prepare("
            INSERT INTO doctor (user_id) VALUES (:user_id)
        ");

        foreach ($doctors as $user_id) {
            $insertStmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $insertStmt->execute();
        }

        return count($doctors); // Return how many doctors were inserted
    }

    // Returns patient info by patient ID
    public function getPatientInfo($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                p.patient_id,
                u.id AS user_id,
                u.name AS account_name,
                u.email,
                u.role,
                u.created_at AS user_created_at,
                up.firstname,
                up.middlename,
                up.lastname,
                up.gender,
                up.user_address,
                up.postal_code,
                up.phone_number,
                up.blood_type,
                up.date_of_birth,
                up.place_of_birth,
                up.created_at AS profile_created_at,
                up.updated_at AS profile_updated_at
            FROM patient p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN user_profiles up ON up.user_id = u.id
            WHERE u.id = :user_id
        ");

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPatientIdByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT patient_id FROM patient WHERE patient.user_id = :user_id LIMIT 1");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); 
    }

    public function getPatientMedicalRecords($patient_id) {
        $stmt = $this->db->prepare("
            SELECT 
                mr.medical_record_id,
                s.name AS service_name,
                mr.blood_pressure,
                mr.heart_rate,
                mr.temperature,
                mr.height,
                mr.weight,
                mr.immunization_status,
                mr.follow_up_date,
                mr.diagnostic,
                mr.created_at,
                d.doctor_id,
                u.name AS doctor_name
            FROM medical_record mr
            LEFT JOIN services s ON mr.service_id = s.id
            LEFT JOIN doctor d ON mr.doctor_id = d.doctor_id
            LEFT JOIN users u ON u.id = d.user_id
            WHERE mr.patient_id = :patient_id
            ORDER BY mr.created_at DESC
        ");
        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestMedicalRecordIdByPatientId($patient_id) {
        $stmt = $this->db->prepare("
            SELECT medical_record_id
            FROM medical_record
            WHERE patient_id = :patient_id
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn(); // returns just the ID (or false if none found)
    }

    public function addMedicalRecord($data) {
        $stmt = $this->db->prepare("
            INSERT INTO medical_record (
                service_id,
                patient_id,
                blood_pressure,
                heart_rate,
                temperature,
                height,
                weight,
                immunization_status,
                follow_up_date,
                doctor_id,
                appointment_id,
                diagnostic
            ) VALUES (
                :service_id,
                :patient_id,
                :blood_pressure,
                :heart_rate,
                :temperature,
                :height,
                :weight,
                :immunization_status,
                :follow_up_date,
                :doctor_id,
                :appointment_id,
                :diagnostic
            )
            RETURNING medical_record_id
        ");

        $stmt->bindValue(':service_id', $data['service_id'] ?? null, PDO::PARAM_INT);
        $stmt->bindValue(':patient_id', $data['patient_id'], PDO::PARAM_INT);
        $stmt->bindValue(':blood_pressure', $data['blood_pressure'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':heart_rate', $data['heart_rate'] ?? null, PDO::PARAM_INT);
        $stmt->bindValue(':temperature', $data['temperature'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':height', $data['height'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':weight', $data['weight'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':immunization_status', $data['immunization_status'] ?? null, PDO::PARAM_STR);
        
        $follow_up_date = empty($data['follow_up_date']) ? null : $data['follow_up_date'];
        $stmt->bindValue(':follow_up_date', $follow_up_date, $follow_up_date === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        $stmt->bindValue(':doctor_id', $data['doctor_id'], PDO::PARAM_INT);
        $stmt->bindValue(':appointment_id', $data['appointment_id'], PDO::PARAM_INT);
        $stmt->bindValue(':diagnostic', $data['diagnostic'] ?? null, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchColumn(); // returns the new medical_record_id
    }

    // Add a prescription for a given medical record
    public function addPrescription($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO prescription (medical_record_id, dosage, frequency, prescription_name, duration)
            VALUES (:medical_record_id, :dosage, :frequency, :prescription_name, :duration)
            RETURNING prescription_id
        ");
        $stmt->bindValue(':medical_record_id', $data['medical_record_id'], PDO::PARAM_INT);
        $stmt->bindValue(':dosage', $data['dosage'], PDO::PARAM_STR);
        $stmt->bindValue(':frequency', $data['frequency'], PDO::PARAM_STR);
        $stmt->bindValue(':prescription_name', $data['prescription_name'], PDO::PARAM_STR);
        $stmt->bindValue(':duration', $data['duration'], PDO::PARAM_STR); // INTERVAL as string (e.g. '7 days')
        $stmt->execute();
        return $stmt->fetchColumn(); // returns the new prescription_id
    }

    // Get all prescriptions for a medical record
    public function getPrescriptionsByMedicalRecord($medical_record_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM prescription WHERE medical_record_id = :medical_record_id");
        $stmt->bindValue(':medical_record_id', $medical_record_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a prescription by ID
    public function deletePrescription($prescription_id)
    {
        $stmt = $this->db->prepare("DELETE FROM prescription WHERE prescription_id = :prescription_id");
        $stmt->bindValue(':prescription_id', $prescription_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getMedicalRecordById($medical_record_id) {
        $stmt = $this->db->prepare("SELECT * FROM medical_record WHERE medical_record_id = :medical_record_id LIMIT 1");
        $stmt->bindValue(':medical_record_id', $medical_record_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByPatientId($patient_id) {
        $stmt = $this->db->prepare("SELECT u.id, u.name, u.email FROM users u JOIN patient p ON u.id = p.user_id WHERE p.user_id = :patient_id");
        $stmt->bindValue(':patient_id', $patient_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasMedicalRecordForAppointment($appointment_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM medical_record WHERE appointment_id = :appointment_id");
        $stmt->bindValue(':appointment_id', $appointment_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function getTotalPatients() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM patient");
        $stmt->execute();
        if ($stmt) {
            return $stmt->fetchColumn();
        }
        return 0; // or handle error as needed
    }

    // ------------------------------ Medication Tracking ------------------------------
    // Get all prescriptions that are still active (not expired)
    public function getActivePrescriptionsForReminders() {
        $stmt = $this->db->prepare("
            SELECT 
                p.prescription_id,
                p.medical_record_id,
                p.dosage,
                p.frequency,
                p.prescription_name,
                p.duration,
                u.email,
                up.firstname AS patient_name,
                (
                    SELECT sent_at 
                    FROM prescription_reminder_log 
                    WHERE prescription_id = p.prescription_id 
                    ORDER BY sent_at DESC 
                    LIMIT 1
                ) AS last_reminder_sent_at
            FROM prescription p
            JOIN medical_record mr ON p.medical_record_id = mr.medical_record_id
            JOIN patient pat ON mr.patient_id = pat.patient_id
            JOIN users u ON pat.user_id = u.id
            LEFT JOIN user_profiles up ON up.user_id = u.id
            WHERE p.duration IS NULL OR p.time + p.duration > NOW()
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Log that a reminder was sent
    public function logPrescriptionReminder($prescription_id) {
        $stmt = $this->db->prepare("
            INSERT INTO prescription_reminder_log (prescription_id, sent_at)
            VALUES (:prescription_id, NOW())
        ");
        $stmt->bindValue(':prescription_id', $prescription_id, PDO::PARAM_INT);
        $stmt->execute();
    }

}