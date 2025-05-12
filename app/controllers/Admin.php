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
        }
    
        $this->redirect->to('pages/doctorappointments');
        exit;
    }
}