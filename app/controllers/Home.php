<?php

class Home extends Controller{

    public $usermodel;
    public $authmodel;

    public $request;

    public $redirect;

    public function __construct(Request $request = null) {
        $this->usermodel = $this->model('UserModel');
        $this->authmodel = $this->model('AuthModel');
        $this->request = $request !== null ? $request : new Request; 
        $this->redirect = new Redirect;
    }


    public function index() {
        // 🔒 Redirect to signin page if user is not logged in
        if (!Session::getIsLoggedIn()) {
            return $this->redirect->to('pages/home');
        }
    
        if ($this->request->isPost()) {
            $name = $this->request->data('name');
            $email = $this->request->data('email');
    
            $insert = $this->usermodel->add($name, $email);
    
            // You can add success or error messages here as needed
            return $this->redirect->to('pages/home');
        }
    
        // ✅ User is authenticated, show welcome view
        $this->view('pages/home');
    }
    
}