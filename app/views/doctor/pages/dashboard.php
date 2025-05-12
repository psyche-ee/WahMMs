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
        <div class="search">
            <form action="">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="text" placeholder="Search for patients..." name="search" id="search">
            </form>
        </div>

        <div class="sched_appointments">
            <div class="scheduleToday">
                <h4>Schedule Appointments Today</h4>
                <table class="appointments_table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Service</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?= htmlspecialchars($appointment['firstname'] . ' ' . $appointment['lastname']) ?></td>
                                <td><?= htmlspecialchars($appointment['name']) ?></td>
                                <td><?= date("g:i A", strtotime($appointment['appointment_time'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total_appointments">
                <div class="data">
                    <h1>10</h1>
                </div>
                <div class="title">
                    <p>Total Appointments</p>
                </div>
            </div>

            <div class="total_patients">
                <div class="data">
                    <h1>100</h1>
                </div>
                <div class="title">
                    <p>Total Patients</p>
                </div>
            </div>
        </div>

        <div class="quickActions">
            <div class="action_Buttons">
                <h3>Quick Actions</h3>

                <div class="button_container">
                    <div class="addPatient">
                        <a href="add_patient.php"><i class="fa-solid fa-user-plus"></i></a>
                        <p>Add New Patient</p>
                    </div>

                    <div class="addPatient">
                        <a href="add_patient.php"><i class="fa-solid fa-user-plus"></i></a>
                        <p>Add New Patient</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
</body>
</html>