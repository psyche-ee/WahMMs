<?php

/**
 * ----------------------------------------------------------
 * Base URL
 * ----------------------------------------------------------
 */

function baseurl() {
    return 'https://wahmms-nkj7.onrender.com';
}

/**
 * ----------------------------------------------------------
 * App General Configuration
 * ----------------------------------------------------------
 */

$GLOBALS['config'] = array(

    //configuration for database connection
    "database" => array(  // Changed from "mysql" to more generic "database"
        "db_host" => "aws-0-ap-southeast-1.pooler.supabase.com",
        "db_user" => "postgres.vmkcpsojjqyodnaflgof",
        "db_pass" => "&z&.5tyFsQ+R96.",
        "db_name" => "postgres",
        "db_type" => "pgsql"  // Add this to specify database type
    ),

    //configuration for cookie
    "cookie" => array(
        "cookie_expiry" => 1209600,
        "session_cookie_expiry" => 604800,
        "cookie_domain" => "",
        "cookie_path" => "/",
        "cookie_secure" => true,
        "cookie_http" => true,
        "cookie_secret_key" => "af&70-GF^!a{f64r5@g38l]#kQ4B+43%"
    ),

    //configuration for encryption keys
    "encryption" => array(
        "encryption_key" => "3fT!9@eL#4vR%8c2*7m%Qa0nZ1p",
        "hmac_salt" => "b7E3d8r5*Lm!9v@4Yq6&Mp2zZx1o",
        "hash_key" => "R8nP6qW!7g#Xz3Ml"
    ),

    //configuration for hashing strength
    "hashing" => array(
        "hash_cost_factor" => "10"
    ),

    //configuration for email server credentials
    //emails are sent using SMTP
    "mailer" => array(
        "email_smtp_debug" => 0,
        "email_smtp_auth" => true,
        "email_smtp_secure" => "ssl",
        "email_smtp_host" => "smtp.hostinger.com",
        "email_smtp_username" => "med@wahmms.site",
        "email_smtp_password" => "@Rw/HP4GH57",
        "email_smtp_port" => "465",
        "email_from" => "med@wahmms.site",
        "email_from_name" => "WahMMs",
        "email_reply_to" => "med@wahmms.site",

        //configuration for email verification
        "email_email_verification" => "1",
        "email_email_verification_url" => baseurl() . "/auth/verifyuser",
        "email_email_verification_subject" => "Please verify your account",

        //configuration for reset password
        "email_password_reset" => "2",
        "email_password_reset_url" => "https://4182-202-90-133-67.ngrok-free.app/auth/resetpassword",
        "email_password_reset_subject" => "Reset your password",

        //configuration for login notification
        "email_login_notification" => "3",
        "email_login_notification_subject" => "Successful Login Notification",

        //successful booking notification
        "email_booking_notification" => "4",
        "email_booking_notification_subject" => "Successful Booking Notification",

        //appointment status update notification
        "email_appointment_status_notification" => "5",
        "email_booking_notification_subject" => "Appointment Status Notification",

        // prescription notification
        "email_prescription_notification" => "6",
        "email_prescription_notification_subject" => "New Prescription Added",

        // medication reminder
        "email_medication_reminder" => "7",
        "email_medication_reminder_subject" => "Medication Reminder"

        // // password changed notification
        // 'mailer/email_password_changed' => "password_changed",
        // 'mailer/email_password_changed_subject' => 'Your Password Was Changed',
    ),


);