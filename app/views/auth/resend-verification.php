<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/All.css">
</head>
<body>
    <div class="form" id="forgot-form">
        <div class="close_btn" onclick="window.location.href='<?= baseurl() ?>';">
            <p>&times;</p>
        </div>
        <h2>Verify Account</h2>
        <p><?= $_SESSION['verify_notice'] ?? 'Your email is not verified yet.' ?></p>

        <form method="post" action="<?= baseurl() ?>/auth/resendverification">
            <button type="submit">Resend Verification Email</button>
        </form>
        <form action="<?= baseurl() ?>/pages/home" method="POST">
            <button id="back_login">Back to Login Page</button>
        </form>
    </div>
</body>
</html>

