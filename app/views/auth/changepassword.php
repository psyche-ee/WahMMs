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
            <p class="success-msg" id="successMessage">Password updated successfully!</p>
            <script>
                setTimeout(() => {
                    const msg = document.getElementById('successMessage');
                    if (msg) msg.style.display = 'none';
                }, 5000);
            </script>
            <?php unset($_SESSION['updated']); ?>
        <?php else: ?>
            <p class="success-msg" style="visibility: hidden;">.</p>
        <?php endif; ?>

        <form action="<?= baseurl() ?>/auth/changepassword" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <div class="input-group">
                <input type="password" name="password" placeholder="New Password">
                <div class="error-msg"><?= $data['errnewpassword'] ?? '&nbsp;' ?></div>
            </div>

            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm New Password">
                <div class="error-msg"><?= $data['errconfirm_password'] ?? '&nbsp;' ?></div>
            </div>

            <button type="submit" id="updateBtn">Update Password</button>
            <a href="<?= baseurl() ?>/pages/home">Cancel</a>
        </form>
    </div>
</body>
</html>
