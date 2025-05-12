<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/Home.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/All.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/header.php'; ?>

    <section>
        <div class="left">
            <div class="herotext">
                <h1>Wahing Medical Clinic</h1>
                <p>"Quality Healthcare is our Priority"</p>
            </div>

            <div class="sched_overlay">
                <div class="day">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p><?= $today['day'] ?></p>
                </div>

                <div class="time">
                    <i class="fa-regular fa-clock"></i>
                    <p>
                        <?= date("g:i A", strtotime($today['open_time'])) ?>
                        -
                        <?= date("g:i A", strtotime($today['close_time'])) ?>
                    </p>
                </div>

                <div class="open">
                    <p><?= $today['is_open'] ? 'Now Open' : 'Closed' ?></p>
                    <div class="circle <?= $today['is_open'] ? 'green' : 'red' ?>"></div>
                </div>

                <div class="appointBtn">
                    <form action="<?= baseurl() ?>/pages/services" method="POST">
                        <a href=""><button class="appBtn">Make an Appointment</button></a>
                    </form>
                </div>
            </div>

        </div>

        <div class="right">
            <div class="img">
                <img src="<?= baseurl()?>/public/assets/Home/wahing_face.png" alt="Wahing Image">
            </div>
        </div>
    </section>
</body>
</html>
<script src="<?= baseurl()?>/public/scripts/Wahing.js"></script>