<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/appointments.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .btn-cancel-all {
            background: #D81616;
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.07);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-cancel-all:hover {
            background: #b31313;
        }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../common/sidebar.php'; ?>

    <main>
        <div class="table_container">
            <h1>Confirmed Appointments</h1>
            <form method="post" action="<?= baseurl() ?>/admin/autocancelappointments" style="margin-bottom: 20px; text-align: right;">
                <button type="submit" class="btn-cancel-all">
                    <i class="fa fa-ban"></i> Cancel All Unattended Appointments
                </button>
            </form>
            <div class="table_scroll">
                <table class="appointments_table">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Service</th>
                            <th>Appointment Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($appointments)): ?>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr 
                                    class="clickable-row" 
                                    data-href="<?= baseurl() ?>/admin/patientInfo/<?= $appointment['user_id'] ?>" style="cursor: pointer;">
                                    <td><?= htmlspecialchars($appointment['lastname']) . ', ' . $appointment['firstname'] . ' ' . $appointment['middlename'] ?></td>
                                    <td><?= htmlspecialchars($appointment['name']) ?></td>
                                    <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                                    <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                                    <td>
                                        <span class="status <?= strtolower($appointment['status']) ?>">
                                            <?= ucfirst($appointment['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Action buttons -->
                                        <?php if ($appointment['status'] === 'pending'): ?>
                                            <form method="post" action="<?= baseurl() ?>/admin/Action" style="display:inline;">
                                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                <input type="hidden" name="action" value="confirmed">
                                                <button type="submit" class="btn-accept">Accept</button>
                                            </form>
                                            <form method="post" action="<?= baseurl() ?>/admin/Action" style="display:inline;">
                                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                <input type="hidden" name="action" value="cancelled">
                                                <button type="submit" class="btn-decline">Decline</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post" action="<?= baseurl() ?>/admin/Action" style="display:inline;">
                                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                <input type="hidden" name="action" value="completed">
                                                <button type="submit" class="btn-accept">Completed</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No confirmed appointments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </main>
    
</body>
</html>
<script src="<?= baseurl()?>/public/scripts/DoctorWahing.js"></script>