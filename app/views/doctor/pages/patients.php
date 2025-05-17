<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl() ?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl() ?>/public/styles/doctor/appointments.css">
    <link rel="stylesheet" href="<?= baseurl() ?>/public/styles/doctor/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/sidebar.php'; ?>

    <main>
        <div class="table_container">
            <h1>Patients</h1>
            <div class="table_scroll">
                <table class="appointments_table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Name</th>
                            <!-- <th>Gender</th>
                            <th>Age</th> -->
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Appointment Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($patients)): ?>
                            <?php foreach ($patients as $patient): ?>
                                <tr>
                                    <td><?= htmlspecialchars($patient['user_id']) ?></td>
                                    <td><?= htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']) ?></td>
                                    <!-- <td><?= htmlspecialchars($patient['gender']) ?></td>
                                    <td>
                                        <?php
                                            $dob = new DateTime($patient['date_of_birth']);
                                            $today = new DateTime();
                                            $age = $today->diff($dob)->y;
                                            echo htmlspecialchars($age) . ' years';
                                        ?>
                                    </td> -->
                                    <td><?= htmlspecialchars($patient['phone_number']) ?></td>
                                    <td><?= htmlspecialchars($patient['email']) ?></td>
                                    <td><?= htmlspecialchars($patient['appointment_date']) ?></td>
                                    <td><?= htmlspecialchars($patient['appointment_time']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align:center; color: gray; padding: 20px;">
                                    No completed appointments found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
