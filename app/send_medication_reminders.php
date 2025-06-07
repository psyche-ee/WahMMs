<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/start.php';
require_once __DIR__ . '/models/AdminModel.php';

$adminModel = new AdminModel();

// 1. Get all active prescriptions
$prescriptions = $adminModel->getActivePrescriptionsForReminders();

foreach ($prescriptions as $prescription) {
    // 2. Check if a reminder should be sent now
    if (shouldSendReminder($prescription)) {
        
        Email::sendEmail(
            Config::get('mailer/email_medication_reminder'),
            $prescription['email'],
            ['name' => $prescription['patient_name']],
            [
                'prescription_name' => $prescription['prescription_name'],
                'dosage' => $prescription['dosage'],
                'frequency' => $prescription['frequency']
            ]
        );
        // 4. Log the reminder
        $adminModel->logPrescriptionReminder($prescription['prescription_id']);
    }
}

// Helper: decide if reminder is due
function shouldSendReminder($prescription) {
    // Example: send if no reminder sent in the last interval
    $lastSent = $prescription['last_reminder_sent_at'];
    $frequency = $prescription['frequency']; // e.g., "every 8 hours"
    $interval = parseFrequencyToSeconds($frequency);

    if (!$lastSent) return true; // never sent
    return (time() - strtotime($lastSent)) >= $interval;
}

// Helper: convert frequency string to seconds
function parseFrequencyToSeconds($frequency) {
    // You can improve this parser as needed
    if (preg_match('/every (\d+) minutes?/', $frequency, $m)) {
        return $m[1] * 60;
    }
    if (preg_match('/every (\d+) hours?/', $frequency, $m)) {
        return $m[1] * 3600;
    }
    if (preg_match('/once a day/', $frequency)) {
        return 86400;
    }
    // Add more rules as needed
    return 86400; // default: once a day
}