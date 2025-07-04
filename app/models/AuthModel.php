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

    public function updateUserProfile($userId, $firstname, $lastname, $middlename, $gender, $user_address, $postal_code, $phone_number, $blood_type, $date_of_birth, $place_of_birth) {
        $this->db->prepare("UPDATE user_profiles SET 
            firstname = :firstname,
            lastname = :lastname,
            middlename = :middlename,
            gender = :gender,
            user_address = :user_address,
            postal_code = :postal_code,
            phone_number = :phone_number,
            blood_type = :blood_type,
            date_of_birth = :date_of_birth,
            place_of_birth = :place_of_birth,
            updated_at = NOW()
            WHERE user_id = :user_id
        ");
        $this->db->bindValue(':user_id', $userId);
        $this->db->bindValue(':firstname', $firstname);
        $this->db->bindValue(':lastname', $lastname);
        $this->db->bindValue(':middlename', $middlename);
        $this->db->bindValue(':gender', $gender);
        $this->db->bindValue(':user_address', $user_address);
        $this->db->bindValue(':postal_code', $postal_code);
        $this->db->bindValue(':phone_number', $phone_number);
        $this->db->bindValue(':blood_type', $blood_type);
        $this->db->bindValue(':date_of_birth', $date_of_birth);
        $this->db->bindValue(':place_of_birth', $place_of_birth);
        
        return $this->db->execute();
    }
    

    public function login($email, $password, $userIp, $userAgent) {
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

        if ($user["is_email_activated"] == 0 ) {
            // Store info in session so user can request a resend link
            Session::set('unverified_email', $email);
            Session::set('warning', 'Your email is not verified. Please verify to continue.');
            return 'unverified'; // Signal for controller to redirect
        }

        Session::reset(["user_id" => $userId, "ip" => $userIp, "user_agent" => $userAgent]);
        
        $_SESSION['user_id'] = $user['id']; // Store user ID in session

        $date = date('js F, Y - h:i:s A');
        $deviceInfo = Email::getDeviceInfo();
        Email::sendEmail(Config::get('mailer/email_login_notification'), $email, ["name" => $user["name"]], ["date" =>time(), 'browser' =>$deviceInfo['browser'], "os" => $deviceInfo['os']]);

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

    public function isEmailExists($email) {
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

        $now = time();
        $lastVerification = is_numeric($user['email_last_verification'])
            ? (int)$user['email_last_verification']
            : strtotime($user['email_last_verification']);

        if ($user['email_token'] === $emailToken && ($now - $lastVerification) <= 900) {
            return true;
        } else {
            error_log("Invalid or expired token for user ID: $userId.");
            return false;
        }
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
        $this->db->prepare("SELECT * FROM users WHERE email = :email AND is_email_activated = false LIMIT 1");
        $this->db->bindValue(':email', $email);
        $this->db->execute();
        $user = $this->db->fetchAssociative();

        if (!$user) {
            return false;
        }

        // Cooldown: 2 minutes (120 seconds)
        $now = time();
        if (!empty($user['email_last_verification']) && ($now - $user['email_last_verification']) < 120) {
            // Too soon to resend
            return 'cooldown';
        }

        // Use email_token and generate a new one if missing
        if (empty($user['email_token'])) {
            $token = bin2hex(random_bytes(32));
            $this->db->prepare("UPDATE users SET email_token = :token, email_last_verification = :now WHERE id = :id");
            $this->db->bindValue(':token', $token);
            $this->db->bindValue(':now', $now);
            $this->db->bindValue(':id', $user['id']);
            $this->db->execute();
        } else {
            $token = $user['email_token'];
            // Always update the timestamp when resending
            $this->db->prepare("UPDATE users SET email_last_verification = :now WHERE id = :id");
            $this->db->bindValue(':now', $now);
            $this->db->bindValue(':id', $user['id']);
            $this->db->execute();
        }

        $encryptedId = Encryption::encrypt($user['id']);
        $verificationLink = baseurl() . "auth/verifyuser?id={$encryptedId}&token={$token}";

        return Email::sendEmail(
            Config::get('mailer/email_email_verification'),
            $email,
            ["name" => $user['name'], "id" => $user['id']],
            ["email_token" => $token]
        );
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

        // 15 minutes = 900 seconds
        if ((time() - $record['password_last_reset']) > 900) {
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
    
        return true;
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDoctorByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT d.doctor_id, u.name FROM doctor d JOIN users u ON d.user_id = u.id WHERE d.user_id = :user_id");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}