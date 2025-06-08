<?php
$record_exists = isset($_GET['error']) && $_GET['error'] === 'record_exists';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/all.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/add_diagnostic.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .modal {
            position: fixed;
            z-index: 9999;
            left: 0; top: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.4);
            display: flex; align-items: center; justify-content: center;
        }
        .modal-content {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            text-align: center;
            min-width: 300px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
            position: relative;
        }
        .close {
            position: absolute;
            right: 18px;
            top: 10px;
            font-size: 2em;
            color: #888;
            cursor: pointer;
        }
        .close:hover { color: #D81616; }

        .btn-print-cert:hover {
            background: #b31313;
            color: #fff;
        }
    </style>
</head>
<body>
    <a href="<?= baseurl() ?>/pages/doctordashboard" class="back-btn"><i class="fa fa-arrow-left"></i> Back</a>

     <div class="container-wrapper">
        <div class="left-column">
            <div class="patient-info">
                <h1>Patient Info</h1>
                <h2><?= htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']) ?></h2>
                <p>Email: <?= htmlspecialchars($patient['email']) ?></p>
                <p>Gender: <?= htmlspecialchars($patient['gender']) ?></p>
                <p>Blood Type: <?= htmlspecialchars($patient['blood_type']) ?></p>
                <p>Phone: <?= htmlspecialchars($patient['phone_number']) ?></p>
                <p>Address: <?= htmlspecialchars($patient['user_address']) ?></p>
                <p>Postal Code: <?= htmlspecialchars($patient['postal_code']) ?></p>
                <p>Date of Birth: <?= htmlspecialchars($patient['date_of_birth']) ?></p>
                <p>Place of Birth: <?= htmlspecialchars($patient['place_of_birth']) ?></p>
            </div>

            <div class="medical-history">
                <h2>Medical History</h2>

                <?php if (!empty($medical_records)): ?>
                    <table border="1" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Doctor</th>
                                <th>Allergy</th>
                                <th>BP</th>
                                <th>HR</th>
                                <th>Temp</th>
                                <th>Height</th>
                                <th>Weight</th>
                                <th>Immunization</th>
                                <th>Follow-up</th>
                                <th>Diagnostic</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medical_records as $record): ?>
                                <tr>
                                    <td><?= htmlspecialchars(date('Y-m-d', strtotime($record['created_at']))) ?></td>
                                    <td><?= htmlspecialchars($record['service_name']) ?></td>
                                    <td><?= htmlspecialchars($record['doctor_name']) ?></td>
                                    <td><?= htmlspecialchars($record['allergy']) ?></td>
                                    <td><?= htmlspecialchars($record['blood_pressure']) ?></td>
                                    <td><?= htmlspecialchars($record['heart_rate']) ?></td>
                                    <td><?= htmlspecialchars($record['temperature']) ?></td>
                                    <td><?= htmlspecialchars($record['height']) ?></td>
                                    <td><?= htmlspecialchars($record['weight']) ?></td>
                                    <td><?= htmlspecialchars(ucfirst($record['immunization_status'])) ?></td>
                                    <td><?= htmlspecialchars($record['follow_up_date']) ?></td>
                                    <td><?= htmlspecialchars($record['diagnostic']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No medical records found.</p>
                <?php endif; ?>
            </div>
            <a
                href="<?= baseurl() ?>/admin/printMedicalCertificate/<?= $patient['user_id'] ?>/<?php if (!empty($medical_records)) echo $medical_records[0]['medical_record_id']; ?>"
                target="_blank"
                class="btn-print-cert"
                style="display:inline-block; width: 250px; margin:20px 0; background:#D81616; color:#fff; padding:10px 22px; border-radius:5px; text-decoration:none; font-weight:bold;"
            >
                <i class="fa fa-print"></i> Print Medical Certificate
            </a>

            <a
                href="<?= baseurl() ?>/admin/printLaboratoryRequest/<?= $patient['user_id'] ?>/<?php if (!empty($medical_records)) echo $medical_records[0]['medical_record_id']; ?>"
                target="_blank"
                class="btn-print-cert"
                style="display:inline-block; width: 250px; margin:10px 0 20px 0; background:#D81616; color:#fff; padding:10px 22px; border-radius:5px; text-decoration:none; font-weight:bold;"
            >
                <i class="fa fa-flask"></i> Laboratory Request
            </a>

            <a
                href="<?= baseurl() ?>/admin/printReferralNote/<?= $patient['user_id'] ?>/<?php if (!empty($medical_records)) echo $medical_records[0]['medical_record_id']; ?>"
                target="_blank"
                class="btn-print-cert"
                style="display:inline-block; width: 250px; margin:10px 0 20px 0; background:#D81616; color:#fff; padding:10px 22px; border-radius:5px; text-decoration:none; font-weight:bold;"
            >
                <i class="fa fa-file-medical"></i> Referral Note
            </a>

            <a
                href="<?= baseurl() ?>/admin/printPrescriptionNote/<?= $patient['user_id'] ?>/<?php if (!empty($medical_records)) echo $medical_records[0]['medical_record_id']; ?>"
                target="_blank"
                class="btn-print-cert"
                style="display:inline-block; width: 250px; margin:10px 0 20px 0; background:#D81616; color:#fff; padding:10px 22px; border-radius:5px; text-decoration:none; font-weight:bold;"
            >
                <i class="fa fa-prescription"></i> Print Prescription
            </a>

            <a
                href="<?= baseurl() ?>/admin/printMedicalRecordNote/<?= $patient['user_id'] ?>/<?php if (!empty($medical_records)) echo $medical_records[0]['medical_record_id']; ?>"
                target="_blank"
                class="btn-print-cert"
                style="display:inline-block; width: 250px; margin:10px 0 20px 0; background:#D81616; color:#fff; padding:10px 22px; border-radius:5px; text-decoration:none; font-weight:bold;"
            >
                <i class="fa fa-notes-medical"></i> Print Medical Record
            </a>

        </div>

        <div class="right-column">
            <?php if (!$record_exists): ?>
                <div class="medical-record">
                    <h2>Add Medical Record</h2>
                    <form id="medicalRecordForm" action="<?= baseurl() ?>/admin/addDiagnostic/<?= $patient['patient_id'] ?>" method="post">
                        <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id ?? '') ?>">
                        <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($_GET['appointment_id'] ?? '') ?>">
                        <input type="hidden" name="patient_id" value="<?= htmlspecialchars($patient['patient_id'] ?? '') ?>">
                        
                        <div class="form-group">
                            <label>Service</label>
                            <p style="margin:0 0 10px 0; font-weight:600;">
                                <?= htmlspecialchars($service_name ?? 'N/A') ?>
                            </p>
                        </div>

                        <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($_SESSION['doctor_id'] ?? '') ?>">
                        <p style="margin:0 0 10px 0; font-weight:600;">
                            <?= htmlspecialchars($_SESSION['name'] ?? 'Doctor') ?>
                        </p>
                        
                        <div class="form-group">
                            <label>Allergy</label>
                            <input type="text" name="allergy">
                        </div>
                        <div class="form-group">
                            <label>Blood Pressure</label>
                            <input type="text" name="blood_pressure">
                        </div>
                        <div class="form-group">
                            <label>Heart Rate</label>
                            <input type="text" name="heart_rate">
                        </div>
                        <div class="form-group">
                            <label>Temperature</label>
                            <input type="text" name="temperature">
                        </div>
                        <div class="form-group">
                            <label>Height</label>
                            <input type="text" name="height">
                        </div>
                        <div class="form-group">
                            <label>Weight</label>
                            <input type="text" name="weight">
                        </div>
                        <div class="form-group">
                            <label>Immunization Status</label>
                            <input type="text" name="immunization_status">
                        </div>
                        <div class="form-group">
                            <label>Follow-up Date</label>
                            <input type="date" name="follow_up_date">
                        </div>
                        <div class="form-group">
                            <label>Diagnostic</label>
                            <input type="text" name="diagnostic">
                        </div>
                        <button type="submit" class="add-record-btn">Save</button>
                        
                    </form>
                </div>

                <div class="prescription-section">
                    <h2>Add Prescription</h2>
                    <form id="prescriptionForm" action="<?= baseurl() ?>/admin/addPrescription/<?= $patient['patient_id'] ?>" method="post">
                        <input type="hidden" name="medical_record_id" id="medical_record_id">
                        <div id="prescriptions-list">
                            <!-- Prescription items will be added here -->
                        </div>
                        <button type="button" class="add-prescription-btn" onclick="addPrescription()">Add Prescription</button>
                        <button type="submit" class="add-record-btn" style="background:#D81616;">Save Prescriptions</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="notice" style="color: #D81616; font-weight: bold; margin: 30px 0;">
                    A medical record for this appointment already exists. You cannot add another one.
                </div>
            <?php endif; ?>

            <!-- Success Modal -->
                <div id="successModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" id="closeModal">&times;</span>
                        <h2>Success!</h2>
                        <p>Medical record has been added successfully.</p>
                    </div>
                </div>

                <!-- No Prescription Modal -->
                <div id="noPrescriptionModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" id="closeNoPrescriptionModal">&times;</span>
                        <h2>Notice</h2>
                        <p>Please add at least one prescription before saving.</p>
                    </div>
                </div>

                <!-- No Medical Record Modal -->
                <div id="noMedicalRecordModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" id="closeNoMedicalRecordModal">&times;</span>
                        <h2>Notice</h2>
                        <p>No medical record has been added for this appointment. Please add a medical record before marking as complete.</p>
                    </div>
                </div>

                <!-- Record Exists Modal -->
                <div id="recordExistsModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" id="closeRecordExistsModal">&times;</span>
                        <h2>Notice</h2>
                        <p>A medical record for this appointment already exists. You cannot add another one.</p>
                    </div>
                </div>
            
        </div>
    </div>
<script>
    function addPrescription() {
        const container = document.getElementById('prescriptions-list');
        const index = container.children.length;
        const div = document.createElement('div');
        div.className = 'prescription-item';
        div.innerHTML = `
            <input type="text" name="prescriptions[${index}][prescription_name]" placeholder="Prescription Name" required>
            <input type="text" name="prescriptions[${index}][dosage]" placeholder="Dosage" required>
            <input type="text" name="prescriptions[${index}][frequency]" placeholder="Frequency" required>
            <input type="text" name="prescriptions[${index}][duration]" placeholder="Duration (e.g. 7 days, 1 month)" required>
            <button type="button" class="delete-prescription-btn" onclick="this.parentElement.remove()">Delete</button>
        `;
        container.appendChild(div);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Check for ?success=1 in URL
        if (window.location.search.includes('success=1')) {
            document.getElementById('successModal').style.display = 'flex';
        }
        document.getElementById('closeModal').onclick = function() {
            document.getElementById('successModal').style.display = 'none';
            // Optionally, remove ?success=1 from URL
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url.pathname + url.search);
        };

        // Show record exists modal if error=record_exists in URL
        if (window.location.search.includes('error=record_exists')) {
            document.getElementById('recordExistsModal').style.display = 'flex';
        }
        document.getElementById('closeRecordExistsModal').onclick = function() {
            document.getElementById('recordExistsModal').style.display = 'none';
            // Optionally, remove ?error=record_exists from URL
            const url = new URL(window.location);
            url.searchParams.delete('error');
            window.history.replaceState({}, document.title, url.pathname + url.search);
        };
    });

    document.getElementById('medicalRecordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Set the medical_record_id in the prescription form
                document.getElementById('medical_record_id').value = data.medical_record_id;
                // Optionally show your success modal
                document.getElementById('successModal').style.display = 'flex';
                form.querySelector('button[type="submit"]').disabled = true;
            } else {
                alert('Failed to add medical record.');
            }
        });
    });

    document.getElementById('prescriptionForm').addEventListener('submit', function(e) {
        const prescriptionsList = document.getElementById('prescriptions-list');
        if (!prescriptionsList || prescriptionsList.children.length === 0) {
            e.preventDefault();
            document.getElementById('noPrescriptionModal').style.display = 'flex';
            return false;
        }
        // Otherwise, allow form to submit
    });

    document.getElementById('closeNoPrescriptionModal').onclick = function() {
        document.getElementById('noPrescriptionModal').style.display = 'none';
    };
</script>
</body>
</html>