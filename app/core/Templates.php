<?php

class Templates {

    /**
     * Construct the body of Password Reset Email
     * 
     * @access public
     * @static static method
     * @param array $userData
     * @param array $data
     * @return string The body of the email.
     */

     public static function getPasswordResetBody($userData, $data) {
        $body = "";
        $body .= "Dear " .$userData["name"] . ", \n\nYou can reset your password from the following link: ";
        $resetUrl = Config::get('mailer/email_password_reset_url') .
            '?id=' . urlencode(Encryption::encrypt($userData["id"])) .
            '&token=' . urlencode($data["password_token"]);

         $body .= "Reset link: <a href=\"$resetUrl\">Click here</a><br><br>";
        $body .= "\n\nIf you didn't request to reset your password, Please contact the admin directly.";
        $body .= "\n\nRegards\nWahMMs";

        return $body;
     }

    /**
     * Construct the body of Email Verification Email
     * 
     * @access public
     * @static static method
     * @param array $userData
     * @param array $data
     * @return string The body of the email.
     */

   public static function getEmailVerificationBody($userData, $data) {
      $body = "";
      $body .= "Dear " . $userData["name"] . ",<br><br>"; 
      $body .= "Please verify your email by clicking the following link: <br>";
      $body .= '<a href="' 
      . Config::get('mailer/email_email_verification_url') 
      . "?id=" . urlencode(Encryption::encrypt((string)$userData["id"])) 
      . "&token=" . urlencode($data["email_token"]) 
      . '">Verify your email</a><br><br>';
      $body .= "If you didn't add/edit your email, please contact the admin directly.<br><br>";
      $body .= "Regards,<br> WahMMs";
      return $body;
   }
  

    /**
     * Construct the body of Login Notification Email
     * 
     * @access public
     * @static static method
     * @param array $userData
     * @param array $data
     * @return string The body of the email.
     */

     public static function getLoginNotificationBody($userData, $data) {
        $body = "";
        $body .= "Dear " .$userData["name"] . ",<br><br>" . "Our records show that you have made a successful login on to your account" . " <br>with \n\n" . $data["browser"];
        $body .= "<br><br>Regards\nWahMMs";

        return $body;
     }

     public static function getBookingNotificationBody($userData, $data) {
        $body = "";
        $body .= "Dear " .$userData["name"] . ",<br><br>" . "Our records show that you have made a booking on " . $data["date"] . " at " . $data["time"] . " for the service " . $data["service_name"] . ".<br><br>";
        $body .= "If you didn't make this booking, please contact the admin directly.<br><br>";
        $body .= "<br><br>Regards\nWahMMs";

        return $body;
     }
}