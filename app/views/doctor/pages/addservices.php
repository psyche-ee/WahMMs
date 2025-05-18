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
            <a href="<?= baseurl() ?>/pages/manageservices" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back</a>

            <form action="addservice.php" method="post" enctype="multipart/form-data">
                <label for="name">Service Name:</label>
                <input type="text" name="name" id="name" required>

                <label for="description">Short Description:</label>
                <textarea name="description" id="description"></textarea>

                <label for="long_description">Long Description:</label>
                <textarea name="long_description" id="long_description"></textarea>

                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" id="price" required>

                <label for="category">Category:</label>
                <input type="text" name="category" id="category">

                <label for="image">Image:</label>
                <input type="file" name="image" id="image">

                <label for="is_active">Active:</label>
                <input type="checkbox" name="is_active" id="is_active" checked>

                <button type="submit" class="add-btn" >Add</button>
            </form>
        </div>
    </main>
</body>
</html>