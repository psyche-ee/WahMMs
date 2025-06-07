<?php

/**
 * Email Class
 * 
 * Sending Email Via SMTP using PHPMailer
 */

//Import PHPMailer classes into global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {

//This is the constructor for email object
private function __construct(){}


/**
 * send an email
 * 
 * @access public
 * @static static method
 * @param string $type Email constant - check config.php
 * @param string $email
 * @param array $userData
 * @param array $data any associated data with the email
 * @throws Exception If failed to send the email
 */

    public static function getDeviceInfo() {
        // Get the user agent
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        // Parse the user agent for browser and OS
        $browser = "Unknown Browser";
        $os = "Unknown OS";

        if (preg_match('/MSIE|Trident/', $userAgent)) {
            $browser = "Internet Explorer";
        } elseif (str_contains($userAgent, 'Firefox')) {
            $browser = "Firefox";
        } elseif (str_contains($userAgent, 'Chrome')) {
            $browser = "Chrome";
        } elseif (str_contains($userAgent, 'Safari')) {
            $browser = "Safari";
        } elseif (preg_match('/Opera|OPR/', $userAgent)) {
            $browser = "Opera";
        }

        if (str_contains($userAgent, 'Windows NT')) {
            $os = "Windows";
        } elseif (str_contains($userAgent, 'Mac OS X')) {
            $os = "Mac OS";
        } elseif (str_contains($userAgent, 'Linux')) {
            $os = "Linux";
        } elseif (str_contains($userAgent, 'Android')) {
            $os = "Android";
        } elseif (preg_match('/iPhone|iPad|iPod/', $userAgent)) {
            $os = "iOS";
        }

        return [
            'browser' => $browser,
            'os' => $os
        ];
    }

    public static function sendEmail($type, $email, $userData, $data) {
    
    try {
        $mail = new PHPMailer();
        $mail->IsSMTP();

        // $mail->SMTPDebug = Config::get('mailer/email_smtp_debug'); 
        $mail->SMTPAuth     = Config::get('mailer/email_smtp_auth');
        $mail->SMTPSecure   = Config::get('mailer/email_smtp_secure');
        $mail->Host         = Config::get('mailer/email_smtp_host');
        $mail->Port         = Config::get('mailer/email_smtp_port');
        $mail->Username     = Config::get('mailer/email_smtp_username');
        $mail->Password     = Config::get('mailer/email_smtp_password');
        $mail->isHTML(true);

        switch($type) {
            case(Config::get('mailer/email_email_verification')):
                $mail->Body = Templates::getEmailVerificationBody($userData, $data);
                $mail->SetFrom(Config::get('mailer/email_from'), Config::get('mailer/email_from_name'));
                $mail->AddReplyTo(Config::get('mailer/email_reply_to'));
                $mail->Subject = Config::get('mailer/email_email_verification_subject');
                $mail->AddAddress($email);
                break;

            case(Config::get('mailer/email_password_reset')):
                $mail->Body = Templates::getPasswordResetBody($userData, $data);
                $mail->SetFrom(Config::get('mailer/email_from'), Config::get('mailer/email_from_name'));
                $mail->AddReplyTo(Config::get('mailer/email_reply_to'));
                $mail->Subject = Config::get('mailer/email_password_reset_subject');
                $mail->AddAddress($email);
                break;

            case(Config::get('mailer/email_login_notification')):
                $deviceInfo = self::getDeviceInfo();
                $mail->Body = Templates::getLoginNotificationBody($userData, $deviceInfo);
                $mail->SetFrom(Config::get('mailer/email_from'), Config::get('mailer/email_from_name'));
                $mail->AddReplyTo(Config::get('mailer/email_reply_to'));
                $mail->Subject = Config::get('mailer/email_login_notification_subject');
                $mail->AddAddress($email);
                break;

            case(Config::get('mailer/email_booking_notification')):
                $mail->Body = Templates::getBookingNotificationBody($userData, $data);
                $mail->SetFrom(Config::get('mailer/email_from'), Config::get('mailer/email_from_name'));
                $mail->AddReplyTo(Config::get('mailer/email_reply_to'));
                $mail->Subject = Config::get('mailer/email_booking_notification_subject');
                $mail->AddAddress($email);
                break;

            case(Config::get('mailer/email_appointment_status_notification')):
                $mail->Body = Templates::getAppointmentStatusNotificationBody($userData, $data);
                $mail->SetFrom(Config::get('mailer/email_from'), Config::get('mailer/email_from_name'));
                $mail->AddReplyTo(Config::get('mailer/email_reply_to'));
                $mail->Subject = Config::get('mailer/email_booking_notification_subject');
                $mail->AddAddress($email);
                break;

            case(Config::get('mailer/email_prescription_notification')):
                $mail->Body = Templates::getPrescriptionNotificationBody($userData, $data);
                $mail->SetFrom(Config::get('mailer/email_from'), Config::get('mailer/email_from_name'));
                $mail->AddReplyTo(Config::get('mailer/email_reply_to'));
                $mail->Subject = Config::get('mailer/email_prescription_notification_subject');
                $mail->AddAddress($email);
                break;

            case(Config::get('mailer/email_medication_reminder')):
                $mail->Body = Templates::getMedicationReminderBody($userData, $data);
                $mail->SetFrom(Config::get('mailer/email_from'), Config::get('mailer/email_from_name'));
                $mail->AddReplyTo(Config::get('mailer/email_reply_to'));
                $mail->Subject = "Medication Reminder";
                $mail->AddAddress($email);
                break;
                
        }

        if ($mail->Send()) {
            return true;
        } else {
            Session::set('danger', 'Message could not be sent <br><strong>Mailer Error:</strong> ' . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        Session::set('danger', 'Message could not be sent <br><strong>Mailer Error:</strong> ' . $mail->ErrorInfo);
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
    }

}