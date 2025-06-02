<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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

    <hr>
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

</body>
</html>