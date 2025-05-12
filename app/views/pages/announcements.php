<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/Announcements.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/All.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/header.php'; ?>
    
    <main>
        <div class="event">
            <h1 class="header" >Events</h1>

            <?php foreach ($announcements as $announce): ?>
                <?php if ($announce['type'] === 'event'): ?>
                    <div class="contents">
                        <div class="image">
                            <?php if (!empty($announce['photo_path'])): ?>
                                <img src="<?= baseurl() ?>/public/<?= htmlspecialchars($announce['photo_path']) ?>" alt="Event Image">
                            <?php endif; ?>
                        </div>
                        <div class="description">
                            <h1><?= htmlspecialchars($announce['title']) ?></h1>
                            <p><?= nl2br(htmlspecialchars($announce['description'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="promo">
            <h1 class="header">Promos</h1>
            <?php foreach ($announcements as $announce): ?>
                <?php if ($announce['type'] === 'promo'): ?>
                    <div class="promo-descript">
                        <h2><?= htmlspecialchars($announce['title']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($announce['description'])) ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
<script src="<?= baseurl()?>/public/scripts/Wahing.js"></script>