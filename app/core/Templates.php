<?php
   
class Templates
{
    private static function wrapEmailTemplate($content, $title = 'Wahing Medical Clinic')
    {
        $year = date('Y');
        return '
        <div style="font-family: Arial, sans-serif; background: #f7f7f7; margin:0; padding:0;">
          <div style="max-width:480px; margin:30px auto; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.07); border:1px solid #eee;">
            <div style="background:#D81616; color:#fff; padding:24px 32px 16px 32px; text-align:center; border-radius:8px 8px 0 0;">
              <h1 style="margin:0; font-size:1.6rem; letter-spacing:1px;">' . htmlspecialchars($title) . '</h1>
            </div>
            <div style="padding:24px 32px; color:#222; font-size:1rem; line-height:1.6;">' . $content . '</div>
            <div style="background:#f2f2f2; color:#888; text-align:center; font-size:0.9rem; padding:16px 32px; border-radius:0 0 8px 8px;">&copy; ' . $year . ' Wahing Medical Clinic. All rights reserved.</div>
          </div>
        </div>
        ';
    }

    public static function getPasswordResetBody($userData, $data)
    {
        $body = "";
        $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>";
        $body .= "You can reset your password from the following link:<br>";
        $resetUrl = Config::get('mailer/email_password_reset_url') .
            '?id=' . urlencode(Encryption::encrypt($userData["id"])) .
            '&token=' . urlencode($data["password_token"]);
        $body .= '<div style="text-align:center;"><a href="' . $resetUrl . '" style="display:inline-block; background:#D81616; color:#fff !important; text-decoration:none; padding:12px 28px; border-radius:4px; margin-top:18px; font-weight:bold; letter-spacing:1px;">Click here</a></div><br><br>';
        $body .= "If you didn\'t request to reset your password, please contact the admin directly.<br>Regards,<br>WahMMs";
        return self::wrapEmailTemplate($body, 'Reset Password');
    }

    public static function getEmailVerificationBody($userData, $data)
    {
        $body = "";
        $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>";
        $body .= "Please verify your email by clicking the following link: <br>";
        $verifyUrl = Config::get('mailer/email_email_verification_url')
            . "?id=" . urlencode(Encryption::encrypt((string)$userData["id"]))
            . "&token=" . urlencode($data["email_token"]);
        $body .= '<div style="text-align:center;"><a href="' . $verifyUrl . '" style="display:inline-block; background:#D81616; color:#fff !important; text-decoration:none; padding:12px 28px; border-radius:4px; margin-top:18px; font-weight:bold; letter-spacing:1px;">Verify your email</a></div><br><br>';
        $body .= "If you didn\'t add/edit your email, please contact the admin directly.<br><br>";
        $body .= "Regards,<br> WahMMs";
        return self::wrapEmailTemplate($body, 'Email Verification');
    }

    public static function getLoginNotificationBody($userData, $data)
    {
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
        $body .= "If this wasn\'t you, please contact the clinic immediately.<br><br>";
        $body .= "Regards,<br> WahMMs";
        return self::wrapEmailTemplate($body, 'Login Notification');
    }

    public static function getBookingNotificationBody($userData, $data)
    {
        $body = "";
        $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>";
        $body .= "Our records show that you have made a booking on <strong>" . htmlspecialchars($data["date"]) . "</strong> at <strong>" . htmlspecialchars($data["time"]) . "</strong> for the service <strong>" . htmlspecialchars($data["service_name"]) . "</strong>.<br><br>";
        $body .= "If you didn\'t make this booking, please contact the admin directly.<br><br>";
        $body .= "Regards,<br> WahMMs";
        return self::wrapEmailTemplate($body, 'Booking Notification');
    }

    public static function getAppointmentStatusNotificationBody($userData, $data)
    {
        $body = "";
        $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>";
        $body .= "This is to inform you that the status of your appointment on <strong>" . htmlspecialchars($data["date"]) . "</strong> at <strong>" . htmlspecialchars($data["time"]) . "</strong> for the service <strong>" . htmlspecialchars($data["service_name"]) . "</strong> has been <strong>" . htmlspecialchars($data["status"]) . "</strong>.<br><br>";
        $body .= "If you have any questions or concerns, feel free to contact our office.<br><br>";
        $body .= "Regards,<br> WahMMs Team";
        return self::wrapEmailTemplate($body, 'Appointment Status');
    }

    public static function getPrescriptionNotificationBody($userData, $data)
    {
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

    public static function getMedicationReminderBody($userData, $data)
    {
        $body = "";
        $body .= "Dear " . htmlspecialchars($userData["name"]) . ",<br><br>";
        $body .= "This is a friendly reminder to take your medication:<br><br>";
        $body .= "<strong>Medication:</strong> " . htmlspecialchars($data["prescription_name"]) . "<br>";
        $body .= "<strong>Dosage:</strong> " . htmlspecialchars($data["dosage"]) . "<br>";
        $body .= "<strong>Frequency:</strong> " . htmlspecialchars($data["frequency"]) . "<br><br>";
        $body .= "Please follow your prescription as advised by your doctor.<br><br>";
        $body .= "If you have any questions or concerns, please contact the clinic.<br><br>";
        $body .= "Stay healthy!<br>WahMMs";
        return self::wrapEmailTemplate($body, 'Medication Reminder');
    }
}