<?php

class Templates {

   private static function wrapEmailTemplate($content, $title = 'Wahing Medical Clinic') {
      $year = date('Y');
      return '
      <style>
         body { font-family: Arial, sans-serif; background: #f7f7f7; margin:0; padding:0; }
         .email-container { max-width:480px; margin:30px auto; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.07); border:1px solid #eee; }
         .email-header { background:#D81616; color:#fff; padding:24px 32px 16px 32px; text-align:center; }
         .email-header h1 { margin:0; font-size:1.6rem; letter-spacing:1px; }
         .email-body { padding:24px 32px; color:#222; font-size:1rem; line-height:1.6; }
         .email-footer { background:#f2f2f2; color:#888; text-align:center; font-size:0.9rem; padding:16px 32px; }
         .email-btn { display:inline-block; background:#D81616; color:#fff !important; text-decoration:none; padding:12px 28px; border-radius:4px; margin-top:18px; font-weight:bold; letter-spacing:1px; }
      </style>
      <div class="email-container">
         <div class="email-header">
               <h1>' . htmlspecialchars($title) . '</h1>
         </div>
         <div class="email-body">' . $content . '</div>
         <div class="email-footer">&copy; ' . $year . ' Wahing Medical Clinic. All rights reserved.</div>
      </div>
      ';
   }

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
        $body .= "Dear " .$userData["name"] . ", \n\nYou can reset your password from the following link:<br>";
        $resetUrl = Config::get('mailer/email_password_reset_url') .
            '?id=' . urlencode(Encryption::encrypt($userData["id"])) .
            '&token=' . urlencode($data["password_token"]);

         $body .= '<div style="text-align:center;"><a href="' . $resetUrl . '" class="email-btn">Click here</a></div><br><br>';
        $body .= "If you didn't request to reset your password, Please contact the admin directly.";
        $body .= "<br>Regards, <br>WahMMs";

        return self::wrapEmailTemplate($body, 'Reset Password');
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
      $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>"; 
      $body .= "Please verify your email by clicking the following link: <br>";
      $verifyUrl = Config::get('mailer/email_email_verification_url')
         . "?id=" . urlencode(Encryption::encrypt((string)$userData["id"]))
         . "&token=" . urlencode($data["email_token"]);
      $body .= '<div style="text-align:center;"><a href="' . $verifyUrl . '" class="email-btn">Verify your email</a></div><br><br>';
      $body .= "If you didn't add/edit your email, please contact the admin directly.<br><br>";
      $body .= "Regards,<br> WahMMs";
      return self::wrapEmailTemplate($body, 'Email Verification');
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
      $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>";
      $body .= "We noticed a successful login to your account";
      if (!empty($data['browser']) || !empty($data['os'])) {
         $body .= " using";
         if (!empty($data['browser'])) {
               $body .= " <strong>" . htmlspecialchars($data['browser']) . "</strong>";
         }
         if (!empty($data['os'])) {
               $body .= " on <strong>" . htmlspecialchars($data['os']) . "</strong>";
         }
      }
      $body .= ".<br><br>";
      $body .= "If this wasn't you, please contact the clinic immediately.<br><br>";
      $body .= "Regards,<br> WahMMs";
      return self::wrapEmailTemplate($body, 'Login Notification');
   }

     public static function getBookingNotificationBody($userData, $data) {
        $body = "";
        $body .= "Dear " .$userData["name"] . ",<br><br>" . "Our records show that you have made a booking on " . $data["date"] . " at " . $data["time"] . " for the service " . $data["service_name"] . ".<br><br>";
        $body .= "If you didn't make this booking, please contact the admin directly.<br><br>";
        $body .= "<br><br>Regards\nWahMMs";

        return self::wrapEmailTemplate($body, 'Booking Notification');
     }

     public static function getAppointmentStatusNotificationBody($userData, $data) {
      $body = "";
      $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>";
      $body .= "This is to inform you that the status of your appointment on <strong>" . htmlspecialchars($data["date"]) . "</strong> at <strong>" . htmlspecialchars($data["time"]) . "</strong> for the service <strong>" . htmlspecialchars($data["service_name"]) . "</strong> has been <strong>" . htmlspecialchars($data["status"]) . "</strong>.<br><br>";
      $body .= "If you have any questions or concerns, feel free to contact our office.<br><br>";
      $body .= "Regards,<br>WahMMs Team";

      return self::wrapEmailTemplate($body, 'Appointment Status');
   }

   public static function getPrescriptionNotificationBody($userData, $data) {
      $body = "";
      $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>";
      $body .= "A new prescription has been added for you.<br><br>";
      $body .= "<strong>Prescription Details:</strong><br>";
      foreach ($data['prescriptions'] as $prescription) {
         $body .= "Name: " . htmlspecialchars($prescription['prescription_name']) . "<br>";
         $body .= "Dosage: " . htmlspecialchars($prescription['dosage']) . "<br>";
         $body .= "Frequency: " . htmlspecialchars($prescription['frequency']) . "<br><br>";
      }
      $body .= "If you have questions, please contact the clinic.<br><br>";
      $body .= "Regards,<br> WahMMs";
      return self::wrapEmailTemplate($body, 'Prescription');
   }

}