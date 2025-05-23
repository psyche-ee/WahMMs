<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/All.css">
    <style>
        .input-group {
            margin-bottom: 20px;
        }

        .error-msg {
            color: red;
            font-size: 0.85em;
            min-height: 1.2em;
            margin-top: 5px;
            margin-left: 40px;
        }

        .success-msg {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            margin-top: 5px;
        }

        button {
            margin-top: 10px;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="form" id="signup-form">
        <h2>Change Password</h2>

        <?php if (!empty($_SESSION['updated'])): ?>
            <dialog class = "successmodal" id="successModal" open style="padding:2em 3em;text-align:center;border:none;border-radius:8px;box-shadow:0 2px 16px rgba(0,0,0,0.2);">
                <p class="success-msg" style="font-size:1.1em;">Password updated successfully!</p>
                <!-- <button onclick="window.location.href='<?= baseurl() ?>/auth/signin';">Go to Login</button> -->
            </dialog>
            <script>
                // Prevent interaction with the form while modal is open
                document.getElementById('signup-form').style.pointerEvents = 'none';
                // Optionally, you can redirect after a few seconds:
                // setTimeout(() => { window.location.href = '<?= baseurl() ?>/auth/signin'; }, 4000);
            </script>
            <?php unset($_SESSION['updated']); ?>
        <?php endif; ?>

        <form action="<?= baseurl() ?>/auth/updatepassword" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <div class="input-group">
                <input type="password" name="password" placeholder="New Password">
                <div class="error-msg"><?= $data['errpassword'] ?? '&nbsp;' ?></div>
            </div>

            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm New Password">
                <div class="error-msg"><?= $data['errconfirm_password'] ?? '&nbsp;' ?></div>
            </div>

            <button type="submit" id="updateBtn">Update Password</button>
        </form>
    </div>
</body>
</html>
