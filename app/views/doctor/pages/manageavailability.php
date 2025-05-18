<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/manageavailability.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/doctor/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/sidebar.php'; ?>

    <main>
        <div class="day_container">
            <h1>Manage Availability</h1>
            <?php foreach ($businesshours as $hour): ?>
                <div class="day_card" id="day-card-<?= $hour['id'] ?>">
                    <!-- View Mode -->
                    <div class="view_mode" id="view-<?= $hour['id'] ?>">
                        <h3><?= htmlspecialchars($hour['day']) ?></h3>
                        <?php if ($hour['is_open']): ?>
                            <p>Open: <?= date('g:i A', strtotime($hour['open_time'])) ?></p>
                            <p>Close: <?= date('g:i A', strtotime($hour['close_time'])) ?></p>
                            <p>Status: <span style="color: green;">Open</span></p>
                        <?php else: ?>
                            <p>Open: <span>None</span></p>
                            <p>Close: <span>None</span></p>
                            <p>Status: <span>Closed</span></p>
                        <?php endif; ?>
                        <button class="edit-btn" onclick="toggleEdit(<?= $hour['id'] ?>)">Edit</button>
                    </div>

                    <!-- Edit Mode -->
                    <form 
                        action="<?= baseurl() ?>/admin/updatebusinesshour" 
                        method="POST" class="edit_mode" id="edit-<?= $hour['id'] ?>" >

                        <input type="hidden" name="id" value="<?= $hour['id'] ?>">
                        <h3 style="width: 120px;"><?= htmlspecialchars($hour['day']) ?></h3>

                        <label>
                            Open:
                            <input type="time" name="open_time" value="<?= $hour['open_time'] ?>">
                        </label>

                        <label>
                            Close:
                            <input type="time" name="close_time" value="<?= $hour['close_time'] ?>">
                        </label>

                        <label class="status">
                            <input type="checkbox" name="is_open" value="1" <?= $hour['is_open'] ? 'checked' : '' ?>>
                            Open?
                        </label>

                        <button class="save-btn" type="submit">Save</button>
                        <button class="cancel-btn" type="button" onclick="toggleEdit(<?= $hour['id'] ?>)">Cancel</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script>
        function toggleEdit(id) {
            const view = document.getElementById('view-' + id);
            const edit = document.getElementById('edit-' + id);

            if (view.style.display === 'none') {
                view.style.display = 'flex';
                edit.style.display = 'none';
            } else {
                view.style.display = 'none';
                edit.style.display = 'flex';
            }
        }
    </script>                        
    
</body>
</html>