<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/All.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

        #eyeIcon1, #eyeIcon2 {
            color: #555; 
            font-size: 1em;
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

            <div class="input-group" style="position: relative;">
                <div class="error-msg"><?= $data['errpassword'] ?? '&nbsp;' ?></div>
                <input type="password" name="password" id="password" placeholder="Enter password" style="padding-right: 40px;">
                <button type="button" id="togglePassword" class="toggle-password" data-target="password" style="position: absolute; right: 10px; top: 15px; background: none; border: none; cursor: pointer;">
                    <i class="fa-regular fa-eye" id="eyeIcon1"></i>
                </button>
            </div>

            <div class="input-group" style="position: relative;">
                <div class="error-msg"><?= $data['errconfirm_password'] ?? '&nbsp;' ?></div>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" style="padding-right: 40px;">
                <button type="button" id="togglePassword2" class="toggle-password" data-target="confirm_password" style="position: absolute; right: 10px; top: 15px; background: none; border: none; cursor: pointer;">
                    <i class="fa-regular fa-eye" id="eyeIcon2"></i>
                </button>
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

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon1');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        if (type === 'password') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });

    document.getElementById('togglePassword2').addEventListener('click', function () {
        const passwordInput = document.getElementById('confirm_password');
        const eyeIcon = document.getElementById('eyeIcon2');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        if (type === 'password') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });
</script>
