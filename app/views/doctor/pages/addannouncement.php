<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Announcement | Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl() ?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl() ?>/public/styles/doctor/all.css">
    <link rel="stylesheet" href="<?= baseurl() ?>/public/styles/doctor/addannouncement.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/sidebar.php'; ?>
    <main>
        <h2>Add New Announcement</h2>
        <div class="container">
            <a href="<?= baseurl() ?>/pages/manageannouncements" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>

            <form method="post" action="<?= baseurl() ?>/admin/addannouncement" enctype="multipart/form-data">
                <label>Type:
                    <select name="type" required>
                        <option value="">Select type</option>
                        <option value="event">Event</option>
                        <option value="promo">Promo</option>
                    </select>
                </label>

                <label>Title:
                    <input type="text" name="title" placeholder="Title" required>
                </label>

                <label>Description:
                    <textarea name="description" placeholder="Description" required></textarea>
                </label>

                <label>Start Date:
                    <input type="date" name="date_start" required>
                </label>

                <label>End Date:
                    <input type="date" name="date_end">
                </label>

                <label>Photo (optional):
                    <input type="file" name="photo">
                </label>

                <button type="submit" class="add-btn"><i class="fa-solid fa-plus"></i> Add</button>
            </form>
        </div>
    </main>
</body>
</html>