<?php

class Admin extends Controller {
    public $request;
    public $redirect;
    public $adminmodel;

    public function __construct(Request $request = null) {

        $this->request      = $request != null ? $request : new Request();
        $this->redirect     = new Redirect();
        $this->adminmodel    = $this->model('AdminModel');
    }

    public function index() {
        $this->getBusinessHours();

        $this->view('pages/home');
    }

    public function getBusinessHours() {
        $todayHours = $this->adminmodel->getTodayHours();
        $isOpen = $this->adminmodel->isNowOpen();
    }

    public function Action() {
        $appointmentId = $this->request->data('appointment_id');
        $status = $this->request->data('action'); // 'accept', 'decline', or 'completed'

        if ($appointmentId && $status) {
            $appointment = $this->adminmodel->addAction($appointmentId, $status);
            $this->adminmodel->insertConfirmedPatients();

            if ($appointment) {
                // Map readable status
                $statusText = [
                    'confirmed' => 'confirmed',
                    'cancelled' => 'declined',
                    'completed' => 'marked as completed'
                ][$status] ?? $status;

                Email::sendEmail(
                    Config::get('mailer/email_appointment_status_notification'), // Template name
                    $appointment['email'],                                   // Recipient
                    ["name" => $appointment['name']],                        // Variables for greeting
                    [
                        "status" => $statusText,
                        "date" => $appointment['appointment_date'],
                        "time" => $appointment['appointment_time'],
                        "service_name" => $appointment['service_name'] ?? 'your service'
                    ]
                );
            }
        }

        $this->redirect->to('pages/doctorappointments');
        exit;
    }


    public function addannouncement() {
        $photoPath = null;
        if ($this->request->isPost()) {
            $title = $this->request->data('title');
            $description = $this->request->data('description');
            $type = $this->request->data('type');
            $date_start = $this->request->data('date_start');
            $date_end = $this->request->data('date_end') ?? null;
             $result = $this->adminmodel->addAnnouncement($title, $description, $type, $date_start, $date_end, $photoPath);

            if ($result) {
                $this->redirect->to('pages/manageannouncements');
            } else {
                $this->redirect->to('pages/systemerror');
            }
        } 
        $this->view('doctor/pages/addannouncement');
    }

    public function deleteannouncement() {
        if ($this->request->isPost()) {
            $id = $this->request->data('id');
            $result = $this->adminmodel->deleteAnnouncement($id);

            if ($result) {
                $this->redirect->to('pages/manageannouncements');
            } else {
                $this->redirect->to('pages/systemerror');
            }
        } 
    }

    public function addservice() {
        if ($this->request->isPost()) {
            $name = $this->request->data('name');
            $description = $this->request->data('description');
            $long_description = $this->request->data('long_description');
            $price = $this->request->data('price');
            $category = $this->request->data('category');
            $is_active = $this->request->data('is_active') ? true : false;

            // Handle image upload
            $imagePath = $_POST['image_url'] ?? null;
            
            // Call your model's addService method
            $result = $this->adminmodel->addService($name, $description, $long_description, $price, $category, $is_active, $imagePath);

            if ($result) {
                $this->redirect->to('pages/manageservices');
            } else {
                $this->redirect->to('pages/systemerror');
            }
        }
        $this->view('doctor/pages/addservices');
    }

    public function deleteservice() {
        if ($this->request->isPost()) {
            $id = $this->request->data('id');
            $result = $this->adminmodel->deleteService($id);

            if ($result) {
                $this->redirect->to('pages/manageservices');
            } else {
                $this->redirect->to('pages/systemerror');
            }
        } 
    }

    public function updatebusinesshour() {
        if ($this->request->isPost()) {
            $id = $this->request->data('id');
            $open_time = $this->request->data('open_time');
            $close_time = $this->request->data('close_time');
            $is_open = $this->request->data('is_open');
            $result = $this->adminmodel->updateBusinessHour($id, $open_time, $close_time, $is_open);

            if ($result) {
                $this->redirect->to('pages/manageavailability');
            } else {
                $this->redirect->to('pages/systemerror');
            }
        }
    }

    public function patientInfo($id) {
        $data['patient'] = $this->adminmodel->getPatientInfo($id);
        $data['medical_records'] = $this->adminmodel->getPatientMedicalRecords($id);
        $this->view('doctor/pages/get_patient_info', $data);
    }

    public function addDiagnostic($id) {
        $appointment_id = $_GET['appointment_id'] ?? null;
        if ($appointment_id && $this->adminmodel->hasMedicalRecordForAppointment($appointment_id)) {
            // Show error or prevent duplicate
            $this->redirect->to('admin/addDiagnostic/' . $id . '?error=record_exists');
            return;
        }

        $patient = $this->adminmodel->getPatientInfo($id);

        if ($this->request->isPost()) {
            // Collect form data
            $data = [
                'service_id'          => $this->request->data('service_id'), // You may need to map service_name to service_id
                'patient_id'          => $this->request->data('patient_id'),
                'allergy'             => $this->request->data('allergy'),
                'blood_pressure'      => $this->request->data('blood_pressure'),
                'heart_rate'          => $this->request->data('heart_rate'),
                'temperature'         => $this->request->data('temperature'),
                'height'              => $this->request->data('height'),
                'weight'              => $this->request->data('weight'),
                'immunization_status' => $this->request->data('immunization_status'),
                'follow_up_date'      => $this->request->data('follow_up_date'),
                'doctor_id'           => $this->request->data('doctor_id'), // Or however you store the doctor ID in session
                'appointment_id'      => $this->request->data('appointment_id'),
                'diagnostic'          => $this->request->data('diagnostic')
            ];

            // Call the model to insert the record
            $medical_record_id = $this->adminmodel->addMedicalRecord($data);

            if ($this->request->isAjax()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => (bool)$medical_record_id,
                    'medical_record_id' => $medical_record_id
                ]);
                exit;
            }

            if ($medical_record_id) {
                $this->redirect->to('admin/addDiagnostic/' . $id . '?success=1');
            } else {
                $this->redirect->to('admin/addDiagnostic/' . $id . '?error=1');
            }
            return;
        }

        // If not POST, show the form as usual
        $service_id = $_GET['service_id'] ?? null;
        $service_name = $_GET['service_name'] ?? null;

        $data['patient'] = $this->adminmodel->getPatientInfo($id);
        $data['medical_records'] = $this->adminmodel->getPatientMedicalRecords($patient['patient_id']);
        $data['service_id'] = $service_id;
        $data['service_name'] = $service_name;
        $this->view('doctor/pages/add_diagnostic', $data);
    }

    public function addPrescription() {
        if ($this->request->isPost()) {
            $medical_record_id = $this->request->data('medical_record_id');
            $prescriptions = $this->request->data('prescriptions'); // array of prescriptions

            // Fetch patient_id using medical_record_id
            $medical_record = $this->adminmodel->getMedicalRecordById($medical_record_id);
            $patient_id = $medical_record['patient_id'] ?? null;

            if ($medical_record_id && is_array($prescriptions) && count($prescriptions) > 0) {
                foreach ($prescriptions as $prescription) {
                    $data = [
                        'medical_record_id' => $medical_record_id,
                        'dosage' => $prescription['dosage'],
                        'frequency' => $prescription['frequency'],
                        'prescription_name' => $prescription['prescription_name'],
                        'duration' => $prescription['duration'] ?? null
                    ];
                    $this->adminmodel->addPrescription($data);
                }
                
                // After successfully adding prescriptions
                $user = $this->adminmodel->getUserByPatientId($patient_id);
                if ($user) {
                    $userData = [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email']
                    ];
                    $data = [
                        'prescriptions' => $prescriptions // The array you just added
                    ];
                    Email::sendEmail(
                        Config::get('mailer/email_prescription_notification'),
                        $user['email'],
                        $userData,
                        $data
                    );
                }
                // Redirect with success message
                $this->redirect->to('admin/addDiagnostic/' . $user['id'] . '?prescription_success=1');

            } else {
                $this->redirect->to('admin/addDiagnostic/' . $medical_record_id . '?prescription_error=1');
            }
        }
    }

    public function autocancelappointments() {
        
        $this->adminmodel->autoCancelUnattendedAppointments();
        // Optionally, redirect or show a message
        $this->redirect->to('pages/confirmedappointments');
    }

    public function printMedicalCertificate($patient_id, $record_id) {
        $patient = $this->adminmodel->getPatientInfo($patient_id);
        $record = $this->adminmodel->getMedicalRecordById($record_id);
        $doctor_name = $_SESSION['name'] ?? 'Attending Physician';
        $diagnosis = $record['diagnostic'] ?? '';
        $days = $record['rest_days'] ?? '';
        $this->view('doctor/pages/medical_certificate', [
            'patient' => $patient,
            'diagnosis' => $diagnosis,
            'days' => $days,
            'doctor_name' => $doctor_name
        ]);
    }

    public function printLaboratoryRequest($patient_id, $record_id) {
        $patient = $this->adminmodel->getPatientInfo($patient_id);
        $record = $this->adminmodel->getMedicalRecordById($record_id);
        $doctor_name = $_SESSION['name'] ?? 'Attending Physician';
        $this->view('doctor/pages/laboratory_request', [
            'patient' => $patient,
            'record' => $record,
            'doctor_name' => $doctor_name
        ]);
    }

    public function printReferralNote($patient_id, $record_id) {
        $patient = $this->adminmodel->getPatientInfo($patient_id);
        $record = $this->adminmodel->getMedicalRecordById($record_id);
        $doctor_name = $_SESSION['name'] ?? 'Attending Physician';

        $this->view('doctor/pages/referral_note', [
            'patient' => $patient,
            'record' => $record,
            'doctor_name' => $doctor_name
        ]);
    }

    public function printPrescriptionNote($patient_id, $record_id) {
        $patient = $this->adminmodel->getPatientInfo($patient_id);
        $record = $this->adminmodel->getMedicalRecordById($record_id);
        $doctor_name = $_SESSION['name'] ?? 'Attending Physician';
        $prescriptions = $this->adminmodel->getPrescriptionsByMedicalRecord($record_id);

        $this->view('doctor/pages/prescription_note', [
            'patient' => $patient,
            'record' => $record,
            'doctor_name' => $doctor_name,
            'prescriptions' => $prescriptions
        ]);
    }

    public function printMedicalRecordNote($patient_id, $record_id) {
        $patient = $this->adminmodel->getPatientInfo($patient_id);
        $record = $this->adminmodel->getMedicalRecordById($record_id);
        $doctor_name = $_SESSION['name'] ?? 'Attending Physician';
        $prescriptions = $this->adminmodel->getPrescriptionsByMedicalRecord($record_id);

        $this->view('doctor/pages/medical_record_note', [
            'patient' => $patient,
            'record' => $record,
            'doctor_name' => $doctor_name,
            'prescriptions' => $prescriptions
        ]);
    }

}