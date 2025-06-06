<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/dashboard.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/sidebar.php'; ?>

    <main>
        <div class="header"><h1>DR. JESSIE MEDICAL CLINIC</h1></div>
        <!-- <div class="search">
            <form action="<?= baseurl() ?>/admin/searchPatient" method="get">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="text" placeholder="Search for patients..." name="search" id="search">
            </form>
        </div> -->

        <div class="appointments_data">

            <!-- Confirmed Appointments -->
            <a href="<?= baseurl() ?>/pages/confirmedappointments" class="dashboard-link">
                <div class="total_appointments">
                    <div class="data">
                        <h1><?= htmlspecialchars($appointment_data['confirmed_appointments']) ?></h1>
                    </div>
                    <div class="title">
                        <p>Confirmed Appointments</p>
                    </div>
                </div>
            </a>

            <!-- Pending Appointments -->
            <a href="<?= baseurl() ?>/pages/pendingappointments" class="dashboard-link">
                <div class="total_pending_appointments">
                    <div class="data">
                        <h1><?= htmlspecialchars($appointment_data['pending_appointments']) ?></h1>
                    </div>
                    <div class="title">
                        <p>Pending Appointments</p>
                    </div>
                </div>
            </a>

            <!-- Total Patients -->
            <a href="<?= baseurl() ?>/pages/doctorpatients" class="dashboard-link">
                <div class="total_patients">
                    <div class="data">
                        <h1><?= htmlspecialchars($appointment_data['total_patients']) ?></h1>
                    </div>
                    <div class="title">
                        <p>Total Patients</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="scheduleToday">
            <h4>Schedule Appointments Today</h4>
            <table class="appointments_table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Service</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center; color:#888;">No appointments found for today.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr 
                            class="clickable-row"
                            data-href="<?= baseurl() ?>/admin/addDiagnostic/<?= $appointment['user_id'] ?>?appointment_id=<?= $appointment['id'] ?>&service_id=<?= $appointment['service_id'] ?>&service_name=<?= urlencode($appointment['name']) ?>" style="cursor: pointer;">
                            <td><?= htmlspecialchars($appointment['firstname'] . ' ' . $appointment['lastname']) ?></td>
                            <td><?= htmlspecialchars($appointment['name']) ?></td>
                            <td><?= date("g:i A", strtotime($appointment['appointment_time'])) ?></td>
                            <td>
                                <?php if ($appointment['status'] !== 'completed'): ?>
                                    <form action="<?= baseurl() ?>/admin/Action" method="post" style="display:inline;">
                                        <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                        <input type="hidden" name="action" value="completed">
                                        <button type="submit" class="action-btn" style="background:#28a745;color:#fff;border:none;padding:5px 10px;border-radius:4px;cursor:pointer;">Mark as Complete</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: #28a745; font-weight: bold;">Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            </table>
        </div>

    </main>
    
</body>
</html>
<script src="<?= baseurl()?>/public/scripts/DoctorWahing.js"></script>