<?php

class Pages extends Controller {
    
    public $request;
    public $redirect;

    public function __construct(Request $request = null) {

        $this->request      = $request != null ? $request : new Request();
        $this->redirect     = new Redirect();
    }

    public function home() {
        $adminModel = $this->model('AdminModel');
        $today = $adminModel->getTodayHours();
        $today['is_open'] = $adminModel->isNowOpen();

        $authmodel = $this->model('AuthModel');

        $medicalHistory = [];

        $userHasProfile = $authmodel->userHasProfile($_SESSION['user_id'] ?? null);
        if (!$userHasProfile && isset($_SESSION['user_id'])) {
            $data = [];
            $userdata = [];
            
            if ($this->request->isPost()) {
                $userId = $_SESSION['user_id'];
                $firstname = $this->request->data('fname');
                $lastname = $this->request->data('lname');
                $middlename = $this->request->data('mname');
                $barangay = $this->request->data('barangay');
                $municipality = $this->request->data('municipality');
                $city = $this->request->data('city');
                $postal_code = $this->request->data('postal_code');
                $phone_number = $this->request->data('phone_number');
                $blood_type = $this->request->data('blood_type');
                $date_of_birth = $this->request->data('dob');
                $place_of_birth = $this->request->data('pob');
                $confirm = $this->request->data('confirm');

                $rule = new ValidationRules();

                $userdata['fname'] = $firstname;
                $userdata['lname'] = $lastname;
                $userdata['mname'] = $middlename;
                $userdata['barangay'] = $barangay;
                $userdata['municipality'] = $municipality;
                $userdata['city'] = $city;
                $userdata['postal_code'] = $postal_code;
                $userdata['phone_number'] = $phone_number;
                $userdata['blood_type'] = $blood_type;
                $userdata['dob'] = $date_of_birth;
                $userdata['pob'] = $place_of_birth;
                $userdata['confirm'] = $confirm;

                // Validate each input
                if (!$rule->isRequired($firstname)) {
                    $data['errfirstname'] = 'First name cannot be empty.';
                }
                if (!$rule->isRequired($lastname)) {
                    $data['errlastname'] = 'Last name cannot be empty.';
                }
                if (!$rule->isRequired($barangay)) {
                    $data['errbarangay'] = 'Barangay cannot be empty.';
                }
                if (!$rule->isRequired($municipality)) {
                    $data['errmunicipality'] = 'Municipality cannot be empty.';
                }
                if (!$rule->isRequired($city)) {
                    $data['errcity'] = 'City cannot be empty.';
                }
                if (!$rule->isRequired($postal_code) || !$rule->isNumeric($postal_code)) {
                    $data['errpostal_code'] = 'Postal code must be a valid number.';
                }
                if (!$rule->isRequired($phone_number) || !$rule->isPhoneNumber($phone_number)) {
                    $data['errphone_number'] = 'Phone number must be valid.';
                }
                
                if (!$rule->isRequired($date_of_birth) || !$rule->isDate($date_of_birth)) {
                    $data['errdob'] = 'Date of birth must be a valid date.';
                }
                
                if (!$rule->isRequired($confirm)) {
                    $data['errconfirm'] = 'You must confirm that the information is correct.';
                }

                if (empty($data)) {
                    $user_address = $barangay . ', ' . $municipality . ', ' . $city;

                    $result = $authmodel->insertUserProfile($userId, $firstname, $lastname, $middlename, $user_address, $postal_code, $phone_number, $blood_type, $date_of_birth, $place_of_birth);
                    if ($result) {
                        $data['result'] = true;
                        $this->redirect->to('pages/home');
                        exit;
                    } else {
                        $data['result'] = false;
                    }
                }

            }
            $this->view('auth/get-profile', ['data' => $data, 'userdata' => $userdata] );
            return;
        }

        $userInfo = [];
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {

            // Get patient ID
            $patientId = $adminModel->getPatientIdByUserId($_SESSION['user_id']);

            // Get medical records
            $medicalHistory = $adminModel->getPatientMedicalRecords($patientId);
            $usermodel = $this->model('UserModel');
            $userInfo = $usermodel->getUserProfile($_SESSION['user_id']);
        }
        
        $this->view('pages/home', ['today' => $today, 'userInfo' => $userInfo, 'medicalHistory' => $medicalHistory]);
    }

    public function services() {
        $servicesmodel = $this->model('ServicesModel');
        $services = $servicesmodel->getAllServices();
        $loggedIn = Session::getIsLoggedIn();

        $bookingResult = $_SESSION['booking_result'] ?? null;
        unset($_SESSION['booking_result']); // Clear it after reading

        $userInfo = [];
        $medicalHistory = [];
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $usermodel = $this->model('UserModel');
            $adminModel = $this->model('AdminModel');

            $userInfo = $usermodel->getUserProfile($_SESSION['user_id']);
            $patientId = $adminModel->getPatientIdByUserId($_SESSION['user_id']);
            $medicalHistory = $adminModel->getPatientMedicalRecords($patientId);
        }

        $this->view('pages/services', [
            'services' => $services,
            'loggedIn' => $loggedIn,
            'result' => $bookingResult,
            'userInfo' => $userInfo,
            'medicalHistory' => $medicalHistory
        ]);
    }

    public function bookservices() {
        $result = [];
        if ($this->request->isPost()) {
            $userId = $_SESSION['user_id']; // Safely access the ID from the session
            $serviceId = $this->request->data('service-id');
            $serviceName = $this->request->data('service-name');
            $date = $this->request->data('date');
            $time = $this->request->data('time');

            $bookModel = $this->model('BookModel');

            $isBookingSuccessful = $bookModel->bookAppointment($userId, $serviceId, $serviceName, $date, $time);

            $_SESSION['booking_result'] = $isBookingSuccessful ? 'success' : 'failed';
            $this->redirect->to('pages/services');
            exit;
        }
    }

    public function about() {
        $userInfo = [];
        $medicalHistory = [];

        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $usermodel = $this->model('UserModel');
            $adminModel = $this->model('AdminModel');

            $userInfo = $usermodel->getUserProfile($_SESSION['user_id']);
            $patientId = $adminModel->getPatientIdByUserId($_SESSION['user_id']);
            $medicalHistory = $adminModel->getPatientMedicalRecords($patientId);
        }

        $this->view('pages/about', ['userInfo' => $userInfo, 'medicalHistory' => $medicalHistory]);
    }

    public function announcements() {
        $announce = $this->model('AnnounceModel');
        $announceModel = $announce->getAllAnnouncements() ?? [];

        $userInfo = [];
        $medicalHistory = [];

        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $usermodel = $this->model('UserModel');
            $adminModel = $this->model('AdminModel');

            $userInfo = $usermodel->getUserProfile($_SESSION['user_id']);
            $patientId = $adminModel->getPatientIdByUserId($_SESSION['user_id']);
            $medicalHistory = $adminModel->getPatientMedicalRecords($patientId);
        }

        $this->view('pages/announcements', [
            'announcements' => $announceModel,
            'userInfo' => $userInfo,
            'medicalHistory' => $medicalHistory
        ]);
    }

    public function verifiedconfirmation() {
        $this->view('confirmations/verifyuserconfirm');
    }

    public function createdconfirmation() {
        $this->view('confirmations/usercreatedconfirm');
    }

    public function systemerror() {
        $this->view('500');
    }

    public function signin() {
        $this->view('auth/signin');
    }

    public function doctordashboard() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $appointment_data = [];
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getDailyAppointments();
        $appointment_data['confirmed_appointments'] = $adminModel->getTotalAppointments('confirmed');
        $appointment_data['pending_appointments'] = $adminModel->getTotalAppointments('pending');
        $appointment_data['total_patients'] = $adminModel->getTotalPatients();

        $this->view('doctor/pages/dashboard', ['appointments' => $appointments, 'appointment_data' => $appointment_data]);
    }

    public function doctorappointments() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getAllAppointments();

        $this->view('doctor/pages/appointments', ['appointments' => $appointments]);
    }

    public function pendingappointments() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getPendingAppointments();

        $this->view('doctor/pages/pendingappointments', ['appointments' => $appointments]);
    }
    // ---------------------------------------------------------------------------------------------
    public function confirmedappointments() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }

        $adminModel = $this->model('AdminModel');

        // Automatically cancel past confirmed appointments
        $adminModel->cancelPastConfirmedAppointments();

        // Then fetch the updated list of confirmed appointments
        $appointments = $adminModel->getConfirmedAppointments();

        $this->view('doctor/pages/confirmedappointments', ['appointments' => $appointments]);
    }
    // ---------------------------------------------------------------------------------------------
    public function declinedappointments() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getCancelledAppointments();

        $this->view('doctor/pages/cancelledappointments', ['appointments' => $appointments]);
    }

    public function completedappointments() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getCompletedAppointments();

        $this->view('doctor/pages/completedappointments', ['appointments' => $appointments]);
    }

    public function doctorpatients() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $adminModel = $this->model('AdminModel');
        $patients = $adminModel->getAllPatients();

        $this->view('doctor/pages/patients', ['patients' => $patients]);
    }

    public function getBookedTimes() {
        $adminModel = $this->model('AdminModel');
        $date = $_GET['date'] ?? null;

        if ($date) {
            // Call the model to fetch booked times
            $bookedTimes = $adminModel->getBookedTimesByDate($date);

            // Return the booked times as JSON
            header('Content-Type: application/json');
            echo json_encode($bookedTimes);
        } else {
            // Return an empty array if no date is provided
            header('Content-Type: application/json');
            echo json_encode([]);
        }
    }

    public function edit_profile() {
        $data = [];
        $userdata = [];
        $authmodel = $this->model('AuthModel');
        
        if ($this->request->isPost()) {
            $userId = $_SESSION['user_id'];
            $firstname = $this->request->data('fname');
            $lastname = $this->request->data('lname');
            $middlename = $this->request->data('mname');
            $gender = $this->request->data('gender');
            $barangay = $this->request->data('barangay');
            $municipality = $this->request->data('municipality');
            $city = $this->request->data('city');
            $postal_code = $this->request->data('postal_code');
            $phone_number = $this->request->data('phone_number');
            $blood_type = $this->request->data('blood_type');
            $date_of_birth = $this->request->data('dob');
            $place_of_birth = $this->request->data('pob');
            $confirm = $this->request->data('confirm');

            $rule = new ValidationRules();

            $userdata['fname'] = $firstname;
            $userdata['lname'] = $lastname;
            $userdata['mname'] = $middlename;
            $userdata['barangay'] = $barangay;
            $userdata['municipality'] = $municipality;
            $userdata['city'] = $city;
            $userdata['postal_code'] = $postal_code;
            $userdata['phone_number'] = $phone_number;
            $userdata['blood_type'] = $blood_type;
            $userdata['dob'] = $date_of_birth;
            $userdata['pob'] = $place_of_birth;
            $userdata['confirm'] = $confirm;

            // Validate each input
            if (!$rule->isRequired($firstname)) {
                $data['errfirstname'] = 'First name cannot be empty.';
            }
            if (!$rule->isRequired($lastname)) {
                $data['errlastname'] = 'Last name cannot be empty.';
            }
            if (!$rule->isRequired($barangay)) {
                $data['errbarangay'] = 'Barangay cannot be empty.';
            }
            if (!$rule->isRequired($municipality)) {
                $data['errmunicipality'] = 'Municipality cannot be empty.';
            }
            if (!$rule->isRequired($city)) {
                $data['errcity'] = 'City cannot be empty.';
            }
            if (!$rule->isRequired($postal_code) || !$rule->isNumeric($postal_code)) {
                $data['errpostal_code'] = 'Postal code must be a valid number.';
            }
            if (!$rule->isRequired($phone_number) || !$rule->isPhoneNumber($phone_number)) {
                $data['errphone_number'] = 'Phone number must be valid.';
            }
            if (!$rule->isRequired($blood_type)) {
                $data['errblood_type'] = 'Blood type cannot be empty.';
            }
            if (!$rule->isRequired($date_of_birth) || !$rule->isDate($date_of_birth)) {
                $data['errdob'] = 'Date of birth must be a valid date.';
            }
            if (!$rule->isRequired($place_of_birth)) {
                $data['errpob'] = 'Place of birth cannot be empty.';
            }
            if (!$rule->isRequired($confirm)) {
                $data['errconfirm'] = 'You must confirm that the information is correct.';
            }

            if (empty($data)) {
                $user_address = $barangay . ', ' . $municipality . ', ' . $city;

                $result = $authmodel->updateUserProfile($userId, $firstname, $lastname, $middlename, $gender, $user_address, $postal_code, $phone_number, $blood_type, $date_of_birth, $place_of_birth);
                if ($result) {
                    $data['result'] = true;
                    $this->redirect->to('pages/home');
                    exit;
                } else {
                    $data['result'] = false;
                }
            }

        }

        $this->view('auth/edit_profile', ['data' => $data, 'userdata' => $userdata]);
    }

    public function manageannouncements() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $announcements = $this->model('AdminModel')->getAnnouncements();
        $this->view('doctor/pages/manageannouncements', ['announcements' => $announcements]);
    }

    public function manageavailability() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $businesshours = $this->model('AdminModel')->getBusinessHours();
        $this->view('doctor/pages/manageavailability', ['businesshours' => $businesshours]);
    }

    public function manageservices() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'doctor') {
            return $this->redirect->to('pages/home');
        }
        $services = $this->model('AdminModel')->getServices();
        $this->view('doctor/pages/manageservices', ['services' => $services]);
    }

    public function viewMedicalHistory() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            return $this->redirect->to('pages/home');
        }

        $userId = $_SESSION['user_id'];

        // Load models
        $userModel = $this->model('UserModel');
        $adminModel = $this->model('AdminModel');

        // Get user profile
        $userInfo = $userModel->getUserProfile($userId);

        // Get patient ID
        $patientId = $adminModel->getPatientIdByUserId($userId);

        // Get medical records
        $medicalHistory = $adminModel->getPatientMedicalRecords($patientId);

        // Load settings modal or full settings page
        $this->view('pages/settings_medical_history', [
            'userInfo' => $userInfo,
            'medicalHistory' => $medicalHistory,
        ]);
    }


}
