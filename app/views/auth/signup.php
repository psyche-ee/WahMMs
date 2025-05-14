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
            margin-bottom: 5px;
        }

        .error-msg {
            color: red;
            font-size: 0.85em;
            min-height: 1.2em; /* Reserve space */
            margin-top: 5px;
            margin-left: 40px;
        }

        input {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 1em;
        }

        button {
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <p style="color: green; text-align:center; margin-top: 10px;"><?= $data['success'] ?? '&nbsp;' ?></p>
    <div class="form" id="signup-form">
        <h2 class="signuptitle">SIGNUP</h2>
        
        <form action="<?= baseurl() ?>/auth/signup" method="POST">

            <div class="input-group">
                 <div class="error-msg"><?= $data['errname'] ?? '&nbsp;' ?></div>
                <input type="text" name="name" placeholder="Enter username" value="<?= htmlspecialchars($data['name'] ?? '') ?>">
            </div>

            <div class="input-group">
                <div class="error-msg"><?= $data['erremail'] ?? '&nbsp;' ?></div>
                <input type="text" name="email" placeholder="Enter email" value="<?= htmlspecialchars($data['email'] ?? '') ?>">
            </div>

            <div class="input-group">
                <div class="error-msg"><?= $data['errpassword'] ?? '&nbsp;' ?></div>
                <input type="password" name="password" placeholder="Enter password">
            </div>

            <div class="input-group">
                <div class="error-msg"><?= $data['errconfirm_password'] ?? '&nbsp;' ?></div>
                <input type="password" name="confirm_password" placeholder="Confirm Password">
            </div>

            <button type="submit" class="signupbtn">Signup</button>
            <a href="<?= baseurl()?>/pages/signin"><span>Back to Login Page</span></a>
        </form>
    </div>
</body>
</html>
<?php if (!empty($data['success'])): ?>
    <script>
        setTimeout(() => {
            window.location.href = "<?= baseurl() ?>/pages/home";
        }, 3000); // Redirects after 3 seconds
    </script>
<?php endif; ?>
