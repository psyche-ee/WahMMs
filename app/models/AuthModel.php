<?php

class AuthModel extends Model {

    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($name, $email, $password, $confirmPassword) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, array('cost' => Config::get('hashing/hash_cost_factor')));

        $this->db->beginTransaction();
        $query = "INSERT INTO users (name, email, hashed_password, email_token, email_last_verification)" . 
        "VALUES (:name, :email, :hashed_password, :email_token, :email_last_verification)";

        $this->db->prepare($query);
        $this->db->bindValue(':name', $name);
        $this->db->bindValue(':email', $email);
        $this->db->bindValue(':hashed_password', $hashedPassword);

        $token = sha1(uniqid(mt_rand(), true));
        $this->db->bindValue(':email_token', $token);
        $this->db->bindValue(':email_last_verification', time());
        $this->db->execute();

        $id = $this->db->lastInsertedId();
        Email::sendEmail(Config::get('mailer/email_email_verification'), $email, ["name" => $name, "id" => $id], ["email_token" => $token]);
        $this->db->endTransaction();

        return true;
    }

    public function getDb() {
        return $this->db;
    }

    public function userHasProfile($userId) {
        if (empty($userId)) {
            return false; 
        }

        $this->db->prepare("SELECT * FROM user_profiles WHERE user_id = :user_id LIMIT 1");
        $this->db->bindValue(':user_id', $userId);
        $this->db->execute();
        return $this->db->countRows() === 1;
    }

    public function insertUserProfile($userId, $firstname, $lastname, $middlename, $user_address, $postal_code, $phone_number, $blood_type, $date_of_birth, $place_of_birth) {
        $this->db->prepare("INSERT INTO user_profiles (user_id, firstname, lastname, middlename, user_address, postal_code, phone_number, blood_type, date_of_birth, place_of_birth) VALUES (:user_id, :firstname, :lastname, :middlename, :user_address, :postal_code, :phone_number, :blood_type, :date_of_birth, :place_of_birth)");
        $this->db->bindValue(':user_id', $userId);
        $this->db->bindValue(':firstname', $firstname);
        $this->db->bindValue(':lastname', $lastname);
        $this->db->bindValue(':middlename', $middlename);
        $this->db->bindValue(':user_address', $user_address);
        $this->db->bindValue(':postal_code', $postal_code);
        $this->db->bindValue(':phone_number', $phone_number);
        $this->db->bindValue(':blood_type', $blood_type);
        $this->db->bindValue(':date_of_birth', $date_of_birth);
        $this->db->bindValue(':place_of_birth', $place_of_birth);
        
        return $this->db->execute();
    }
    

    public function login($email, $password, $userIp, $userAgent) {
        //$rememberMe,
        $rule = new ValidationRules;

        $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $this->db->bindValue(':email', $email);
        $this->db->execute();
        $user = $this->db->fetchAssociative();

        $userId = isset($user["id"]) ? $user["id"] : null;
        $hashedPassword = isset($user["hashed_password"]) ? $user["hashed_password"] : null;

        if (!$rule->credentials(["user_id" => $userId, "hashed_password" =>$hashedPassword, "password" => $password])) {
            session::set('danger', 'credentials combination doesnt exist');
            return false;
        }

        if ($user["is_email_activated"] == 0) {
            // Store info in session so user can request a resend link
            Session::set('unverified_email', $email);
            Session::set('warning', 'Your email is not verified. Please verify to continue.');
            return 'unverified'; // Signal for controller to redirect
        }

        Session::reset(["user_id" => $userId, "ip" => $userIp, "user_agent" => $userAgent]);
        
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        //if (!empty($rememberMe) && $rememberMe === "rememberme") {
        //    Cookie::reset($userId);
        //} else {
        //    Cookie::remove($userId);
        //}

        $date = date('js F, Y - h:i:s A');

        Email::sendEmail(Config::get('mailer/email_login_notification'), $email, ["name" => $user["name"]], ["date" =>time(), 'browser' =>$userAgent]);

        if ($user["role"] === 'doctor') {
            return 'doctor';
        }

        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];
    }

    // Inside AuthModel.php
    public function isLoggedIn() {
        return Session::getIsLoggedIn();
    }


    public function forgotPassword($email) {

        if ($this->isEmailExists($email)) {
            $user = $this->db->fetchAssociative();

            $this->db->getByUserId("forgotten_passwords", $user["id"]);
            $forgottenPassword = $this->db->fetchAssociative();

            $last_time = isset($forgottenPassword["password_last_reset"]) ? $forgottenPassword["forgotten_password_attempts"] : null;

            $rule = new ValidationRules;

            $count = isset($forgottenPassword["forgotten_password_attempts"]) ? $forgottenPassword["forgotten_password_attempts"] : 0;

            //if (!$rule->attempts([["last_time" => $last_time, "count" => $count]])) {
            //    session::set('danger', 'allowed time has expired');
            //    return false;
            //}

            $newPasswordToken = $this->generateForgottenPasswordToken($user["id"], $forgottenPassword);

            Email::sendEmail(Config::get('mailer/email_password_reset'), $user["email"], ["id" => $user["id"], "name" => $user["name"]], $newPasswordToken);
        }
        return True;
    }

    private function isEmailExists($email) {
        $this->db->prepare("SELECT * FROM users WHERE email = :email AND is_email_activated = TRUE LIMIT 1");
        $this->db->bindValue(':email', $email);
        $this->db->execute();

        return $this->db->countRows() === 1;
    }

    private function generateForgottenPasswordToken($userId, $forgottenPassword) {

        if (!empty($forgottenPassword)) {
            $query = "UPDATE forgotten_passwords SET password_token = :password_token, " .
                    "password_last_reset = :password_last_reset, forgotten_password_attempts = forgotten_password_attempts + 1 " .
                    "WHERE user_id = :user_id";
        } else {
            $query = "INSERT INTO forgotten_passwords (user_id, password_token, password_last_reset, forgotten_password_attempts) " .
                    "VALUES (:user_id, :password_token, :password_last_reset, 1)";
        }

        $passwordToken = sha1(uniqid(mt_rand(), true));

        $this->db->prepare($query);
        $this->db->bindValue(':password_token', $passwordToken);
        $this->db->bindValue(':password_last_reset', time());
        $this->db->bindValue(':user_id', $userId);

        $result = $this->db->execute();

        if (!$result) {
            session::set('danger', 'Could not generate token');
        }

        return ["password_token" => $passwordToken];
    }

    public function isEmailVerificationTokenValid($userId, $emailToken) {
        $user = $this->findUserById($userId);
    
        if (!$user) {
            error_log("User not found with ID: $userId");
            return false;
        }
    
        if ($user['email_token'] === $emailToken) {
            error_log("Email verification token is valid for user ID: $userId");
            return true;
        } else {
            error_log("Invalid token for user ID: $userId. Expected: " . $user['email_token'] . ", Provided: $emailToken");
        }
    
        return false;
    }
    

    // Example: Method to find user by ID (modify to fit your DB structure)
    public function findUserById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bindValue(':id', $id);
        $this->db->execute();
        return $this->db->fetchAssociative();
    }

    public function activateUser($userId) {
        error_log("activateUser() called with userId: " . var_export($userId, true));

        try {
            $this->db->query("UPDATE users SET is_email_activated = TRUE WHERE id = :id");
            $this->db->bindValue(':id', $userId);
            $result = $this->db->execute();
            error_log("Update result: " . var_export($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Activation failed: " . $e->getMessage());
            return false;
        }
    }

    public function resendVerificationEmail($email) {
        $this->db->prepare("SELECT * FROM users WHERE email = :email AND is_email_activated = 0 LIMIT 1");
        $this->db->bindValue(':email', $email);
        $this->db->execute();
        $user = $this->db->fetchAssociative();
    
        if (!$user) {
            return false;
        }
    
        // Use email_token and generate a new one if missing
        if (empty($user['email_token'])) {
            $token = bin2hex(random_bytes(32));
            $this->db->prepare("UPDATE users SET email_token = :token WHERE id = :id");
            $this->db->bindValue(':token', $token);
            $this->db->bindValue(':id', $user['id']);
            $this->db->execute();
        } else {
            $token = $user['email_token'];
        }
    
        $encryptedId = Encryption::encrypt($user['id']);
        $verificationLink = baseurl() . "auth/verifyuser?id={$encryptedId}&token={$token}";
    
        return Email::sendEmail(Config::get('mailer/email_email_verification'), $email, ["name" => $user['name'], "id" => $user['id']], ["email_token" => $token]);
    }

    public function isForgottenPasswordTokenValid($userId, $token) {
        $this->db->prepare("SELECT * FROM forgotten_passwords WHERE user_id = :user_id AND password_token = :token LIMIT 1");
        $this->db->bindValue(':user_id', $userId);
        $this->db->bindValue(':token', $token);
        $this->db->execute();
    
        $record = $this->db->fetchAssociative();
    
        if (!$record) {
            return false;
        }
    
        // Optionally, check for token expiration
        $tokenLifetime = 3600; // e.g. 1 hour
        $currentTime = time();
    
        if (($currentTime - $record['password_last_reset']) > $tokenLifetime) {
            return false;
        }
    
        return true;
    }

    public function updatePassword($userId, $password, $confirmPassword) {
        if ($password !== $confirmPassword) {
            return false;
        }
    
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Use your existing database wrapper ($this->db)
        $this->db->prepare("UPDATE users SET hashed_password = :hashed_password WHERE id = :id");
        $this->db->bindValue(':hashed_password', $hashedPassword);
        $this->db->bindValue(':id', $userId);
        $this->db->execute();
    
        return $this->db->countRows() > 0;
    }

    public function logout($userId) {
        // Destroy user session
        Session::destroy();
    
        // Optionally, remove any cookies
        // Cookie::reset($userId);
    
        return true;
    }

}