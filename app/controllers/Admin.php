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
        $status = $this->request->data('action'); // this is 'accept', 'decline', or 'completed'
    
        if ($appointmentId && $status) {
            $this->adminmodel->addAction($appointmentId, $status);
            $this->adminmodel->insertConfirmedPatients();
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
            $is_active = $this->request->data('is_active');


            // Call your model's addService method
            $result = $this->adminmodel->addService($name, $description, $long_description, $price, $category, $is_active);

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
}