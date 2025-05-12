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

        $userHasProfile = $authmodel->userHasProfile($_SESSION['user_id'] ?? null);
        if (!$userHasProfile && isset($_SESSION['user_id'])) {
            $data = [];
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
            $this->view('auth/get-profile', ['result' => $data] );
            return;
        }
        
        $this->view('pages/home', ['today' => $today]);
    }

    public function services() {
        $servicesmodel = $this->model('ServicesModel');
        $services = $servicesmodel->getAllServices();
        $loggedIn = Session::getIsLoggedIn();

        $bookingResult = $_SESSION['booking_result'] ?? null;
        unset($_SESSION['booking_result']); // Clear it after reading

        $this->view('pages/services', ['services' => $services, 'loggedIn' => $loggedIn, 'result' => $bookingResult]);
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
        $this->view('pages/about');
    }

    public function announcements() {
        $announce = $this->model('AnnounceModel');
        $announceModel = $announce->getAllAnnouncements();
        if (!$announceModel) {
            $announceModel = []; 
        }
       
        $this->view('pages/announcements', ['announcements' => $announceModel]);
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
        $appointments = $this->model('AdminModel')->getDailyAppointments();
        $this->view('doctor/pages/dashboard', ['appointments' => $appointments]);
    }

    public function doctorappointments() {
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getAllAppointments();

        $this->view('doctor/pages/appointments', ['appointments' => $appointments]);
    }

    public function pendingappointments() {
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getPendingAppointments();

        $this->view('doctor/pages/pendingappointments', ['appointments' => $appointments]);
    }

    public function confirmedappointments() {
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getConfirmedAppointments();

        $this->view('doctor/pages/confirmedappointments', ['appointments' => $appointments]);
    }

    public function declinedappointments() {
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getCancelledAppointments();

        $this->view('doctor/pages/cancelledappointments', ['appointments' => $appointments]);
    }

    public function completedappointments() {
        $adminModel = $this->model('AdminModel');
        $appointments = $adminModel->getCompletedAppointments();

        $this->view('doctor/pages/completedappointments', ['appointments' => $appointments]);
    }

    public function doctorpatients() {
        $adminModel = $this->model('AdminModel');
        $patients = $adminModel->getAllPatients();

        $this->view('doctor/pages/patients', ['patients' => $patients]);
    }
}
