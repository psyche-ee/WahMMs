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
            <h2>Manage Services</h2>
            <div class="top-bar">
                <a href="<?= baseurl() ?>/admin/addservice"><i class="fa-solid fa-plus"></i> Add New Service</a>
            </div>

            <?php if (!empty($services)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($services as $service): ?>
                        <?php if (is_array($service)): ?>
                            <tr>
                                <td><?= htmlspecialchars($service['name']) ?></td>
                                <td><?= htmlspecialchars($service['description']) ?></td>
                                <td>₱<?= number_format($service['price'], 2) ?></td>
                                <td><?= htmlspecialchars($service['category']) ?></td>
                                <td><?= $service['is_active'] ? 'Active' : 'Inactive' ?></td>
                                <td>
                                    <form method="post" action="<?= baseurl() ?>/admin/deleteservice" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                        <input type="hidden" name="id" value="<?= $service['id'] ?>">
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No services added yet.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
