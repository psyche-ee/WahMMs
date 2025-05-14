<?php

class Auth extends Controller {

    public $request;
    public $redirect;
    public $authmodel;
    public $usermodel;

    public function __construct(Request $request = null) {

        $this->request      = $request != null ? $request : new Request();
        $this->redirect     = new Redirect();
        $this->authmodel    = $this->model('AuthModel');
        $this->usermodel    = $this->model('UserModel');
    }

    public function index() {
        if ($this->authmodel->isLoggedIn()) {
            return $this->redirect->to('home/');
        }
    }

    public function signup() {
        $data = [];
        if ($this->request->isPost()) {

            $name       = $this->request->data("name");
            $email      = $this->request->data("email");
            $password   = $this->request->data("password");
            $confirmPassword = $this->request->data("confirm_password");

            $rule = new ValidationRules(Database::open_db());

            if (!$rule->isRequired($name)) {
                $data['errname'] = 'username can not be empty.';
            } elseif (!$rule->minLen($name, 3)) {
                $data['errname'] = 'fullname must not be less than 3 characters.';
            } elseif (!$rule->maxLen($name, 30)) {
                $data['errname'] = 'fullname must not be more than 30 characters.';
            } elseif (!$rule->unique($name, ['users', 'name'])) {
                $data['errname'] = 'username is unavailable.';
            }

            if (!$rule->isRequired($email)) {
                $data['erremail'] = 'email can not be empty.';
            } elseif (!$rule->emailUnique($email)) {
                $data['erremail'] = 'email already taken.';
            } elseif (!$rule->email($email)) {
                $data['erremail'] = 'enter a valid email address.';
            }

            if (!$rule->isRequired($password)) {
                $data['errpassword'] = 'password can not be empty.';
            } elseif (!$rule->minLen($password, 5)) {
                $data['errpassword'] = 'password must not be less than 5 characters.';
            } elseif (!$rule->password($password)) {
                $data['errpassword'] = 'password must have atleast a lowercase, uppercase, integer and special character.';
            }

            if (empty($confirmPassword) || !$rule->equal($this->request->data("password"), [$this->request->data("confirm_password")])) {
                $data['errconfirm_password'] = 'passwords do not match.';
            }

            $data['name'] = $name;
            $data['email'] = $email;
            $data['password'] = $password;

            if (empty($data)) {
                $result = $this->authmodel->register($name, $email, $password, $confirmPassword);
                if (!$result) {
                    return $this->view('500');
                } else {
                    $data['success'] = 'Account created successfully. Check your email to activate your account within 24 hours';
                    return $this->view('auth/signup', $data);
                }
                
            }
        }
        $this->view('auth/signup', ['data' => $data]);
    }

    public function verifyuser() {
        $userId = $this->request->query("id");
        $userId = empty($userId) ? null : Encryption::decrypt($this->request->query("id"));
        $token = $this->request->query("token");
    
        $result = $this->authmodel->isEmailVerificationTokenValid($userId, $token);
    
        if (!$result) {
            $data = [];
            $this->view('auth/user-already-verified', $data);
        } else {
            // ✅ Activate the user's account
            $this->authmodel->activateUser($userId);
    
            // Redirect to the confirmation view
            $this->redirect->to('pages/verifiedconfirmation');
        }
    }

    public function userverified() {
        $data = [];
        $this->view('auth/user-verified', $data);
    }

    public function signin() {
        if ($this->authmodel->isLoggedIn()) {
            return $this->redirect->to('pages/home');
        }
    
        $data = [];
    
        if ($this->request->isPost()) {
            $email      = $this->request->data("email");
            $password   = $this->request->data("password");
    
            $rule = new ValidationRules();
            
            if (!$rule->isRequired($email)) {
                $data['erremail'] = 'Email cannot be empty.';
            } elseif (!$rule->email($email)) {
                $data['erremail'] = 'Enter a valid email address.';
            }
    
            if (!$rule->isRequired($password)) {
                $data['errpassword'] = 'Password cannot be empty.';
            }

            $emaildata = $email;
    
            if (empty($data)) {
                $userinfo = $this->authmodel->login($email, $password, $this->request->clientIp(), $this->request->userAgent());
                
                if (is_array($userinfo)) {
                    Session::create(
                        $userinfo['id'],
                        $userinfo['name'],
                        $this->request->clientIp(),
                        $this->request->userAgent()
                    );
                    $_SESSION['email'] = $userinfo['email'];
                    $_SESSION['name'] = $userinfo['name'];
                    return $this->redirect->to('pages/home');
                } elseif ($userinfo === 'unverified') {
                    // Set email in session for resend verification use
                    $_SESSION['unverified_email'] = $email;
                    $_SESSION['verify_notice'] = 'Your account is not verified. Please verify your email.';
                    $this->redirect->to('auth/resendverification');
                    return;
                } elseif ($userinfo === 'doctor') {
                    $this->redirect->to('pages/doctordashboard');
                    return;
                } else {
                    if ($this->authmodel->isEmailExists($email)) {
                        $data['errpassword'] = 'Incorrect password. Please try again.';
                    } else {
                        $data['loginerror'] = 'Invalid email or password.';
                    }
                }
            } 
        }
        $this->view('auth/signin', ['data' => $data, 'emaildata' => $emaildata]);
    }    

    public function forgotpassword() {
        $data = [];

        if ($this->request->isPost()) {

            $email = $this->request->data("email");

            $rule = new ValidationRules();

            if (!$rule->isRequired($email)) {
                $data['erremail'] = 'email can not be empty.';
            } elseif (!$rule->email($email)) {
                $data['erremail'] = 'enter a valid email address.';
            } elseif ($rule->emailUnique($email)) {
                $data['erremail'] = 'this email is not recognized';
            }

            
            $data['email'] = $email;

            if (empty($data)) {
                $result = $this->authmodel->forgotPassword($email);
                if($result) {
                    $_SESSION['success'] = true;
                    Session::set('success', 'reset link have been sent to your registered email, check and validate within 24 hours.');
                    return $this->redirect->to('auth/forgotpassword/');
                } else {
                    $data['erremail'] = 'Something went wrong, try again.';
                }
            }
        }
        $this->view('auth/forgot-password', $data);
    }

    public function resetpassword() {
        
        $userId = $this->request->query("id");
        $userId = empty($userId) ? null : Encryption::decrypt($this->request->query("id"));
        $token = $this->request->query("token");

        $result = $this->authmodel->isForgottenPasswordTokenValid($userId, $token);

        if (!$result) {
            return $this->view('404');
        } else {
            Session::set("user_id_reset_password", $userId);

            $this->view('auth/update-password');
        }
    }

    public function updatepassword() {
        $data = [];
        if ($this->request->isPost()) {
            $password = $this->request->data("password");
            $confirmPassword = $this->request->data("confirm_password");
            
            // Changed from $_SESSION['user_id'] to $_SESSION['user_id_reset_password']
            $userId = $_SESSION['user_id_reset_password'] ?? null;

            if (!$userId) {
                return $this->redirect->to("auth/resetpassword");
            }

            $rule = new ValidationRules();

            if (!$rule->isRequired($password)) {
                $data['errpassword'] = 'Password cannot be empty.';
            } elseif (!$rule->minLen($password, 5)) {
                $data['errpassword'] = 'Password must not be less than 5 characters.';
            } elseif (!$rule->password($password)) {
                $data['errpassword'] = 'Password must have at least a lowercase, uppercase, integer, and special character.';
            }

            if (empty($confirmPassword) || !$rule->equal($password, [$confirmPassword])) {
                $data['errconfirm_password'] = 'Passwords do not match.';
            }

            if (empty($data)) {
                $result = $this->authmodel->updatePassword($userId, $password, $confirmPassword);
                if (!$result) {
                    error_log("Failed to update password for user ID: $userId");
                } else {
                    // Clear the reset password session after successful update
                    unset($_SESSION['user_id_reset_password']);
                    
                    $_SESSION['updated'] = true;
                    $this->view('auth/update-password', $data);
                    exit;
                }
            }
        }
        $this->view('auth/update-password', $data);
    }

    public function changepassword() {
        if ($this->request->isPost()) {
           
            $newPassword = $this->request->data("password");
            $confirmPassword = $this->request->data("confirm_password");

            $rule = new ValidationRules();

           if (!$rule->isRequired($newPassword)) {
                $data['errnewpassword'] = 'New password cannot be empty.';
            } elseif (!$rule->minLen($newPassword, 5)) {
                $data['errnewpassword'] = 'New password must not be less than 5 characters.';
            } elseif (!$rule->password($newPassword)) {
                $data['errnewpassword'] = 'New password must have at least a lowercase, uppercase, integer, and special character.';
            }

            if (empty($confirmPassword) || !$rule->equal($newPassword, [$confirmPassword])) {
                $data['errconfirm_password'] = 'Passwords do not match.';
            }

            if (empty($data)) {
                $result = $this->authmodel->updatePassword($_SESSION['user_id'], $newPassword, $confirmPassword);
                if ($result) {
                    Session::set('success', 'Password changed successfully.');
                    return $this->redirect->to('pages/home');
                } else {
                    Session::set('danger', 'Failed to change password. Please try again.');
                }
            }
        }
        $this->view('auth/update-password', ['data' => $data]);
    }

    public function logout() {
        Session::destroy(); // destroy session
        $this->redirect->to("pages/home"); // redirect to login page
    }

    public function resendverification() {
        if (!isset($_SESSION['unverified_email'])) {
            return $this->redirect->to('auth/signin');
        }
    
        if ($this->request->isPost()) {
            
            $email = $_SESSION['unverified_email'];
        
            $result = $this->authmodel->resendVerificationEmail($email);
    
            if ($result) {
                Session::set('success', 'A new verification link has been sent to your email.');
            } else {
                Session::set('danger', 'Something went wrong. Please try again later.');
            }
    
            return $this->redirect->to('auth/resendverification');
        }
    
        // GET request – just show the page
        $this->view('auth/resend-verification');
    }
    
}