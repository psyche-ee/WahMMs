<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/manageannouncements.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/sidebar.php'; ?>

    <main>
        <div class="container">
            <h2>Manage Announcements</h2>
            <div class="top-bar">
                <a href="<?= baseurl() ?>/admin/addannouncement"><i class="fa-solid fa-plus"></i> Add New</a>
            </div>

            <?php if (!empty($announcements)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($announcements as $announcement): ?>
                        <?php if (is_array($announcement)): ?>
                            <tr>
                                <td><?= htmlspecialchars($announcement['type']) ?></td>
                                <td><?= htmlspecialchars($announcement['title']) ?></td>
                                <td><?= htmlspecialchars($announcement['description']) ?></td>
                                <td><?= htmlspecialchars($announcement['date_start']) ?></td>
                                <td><?= htmlspecialchars($announcement['date_end'] ?? 'N/A') ?></td>
                                <td>
                                    <form method="post" action="<?= baseurl() ?>/admin/deleteannouncement" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                        <input type="hidden" name="id" value="<?= $announcement['id'] ?>">
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No announcements yet.</p>
            <?php endif; ?>
        </div>
    </main>
    
</body>
</html>