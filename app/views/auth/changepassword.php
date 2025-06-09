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

        #eyeIcon1, #eyeIcon2 {
            color: #555; 
            font-size: 1em;
        }

        @media (max-width: 1024px) {
        .form {
            width: 90%;
            height: auto;
            padding: 30px 20px;
        }

        .form h2 {
            font-size: 2.5rem;
            margin: 0 20px 20px;
        }

        .form input,
        .form button,
        .form a {
            margin: 10px 0;
            font-size: 1rem;
        }

        .form input {
            padding: 12px;
        }

        .form button {
            height: 50px;
            font-size: 20px;
        }

        .error-msg {
            margin-left: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        #togglePassword,
        #togglePassword2 {
            right: 15px;
            top: 12px;
        }
    }

    @media (max-width: 600px) {
        .form {
            width: 95%;
            padding: 15px 10px;
        }

        .form h2 {
            font-size: 2rem;
            margin: 0 15px 20px;
        }

        .form input {
            width: 70%;
            padding: 10px;
            font-size: 0.95rem;
        }

        .form button {
            height: 45px;
            font-size: 18px;
        }

        .error-msg {
            font-size: 0.8em;
            margin-left: 15px;
        }

        #togglePassword,
        #togglePassword2 {
            right: 10px;
            top: 10px;
        }

        #password, #confirm_password {
                padding-right: 0;
            }
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
            <p class="success-msg" style="visibility: hidden;">&nbsp;</p>
        <?php endif; ?>

        <form action="<?= baseurl() ?>/auth/changepassword" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <div class="input-group" style="position: relative;">
                <div class="error-msg"><?= $data['errpassword'] ?? '&nbsp;' ?></div>
                <input type="password" name="password" id="password" placeholder="New Password" style="padding-right: 40px;">
                <button type="button" id="togglePassword" style="position: absolute; right: 25px; top: 15px; background: none; border: none; cursor: pointer;">
                    <i class="fa-regular fa-eye" id="eyeIcon1"></i>
                </button>
                
            </div>

            <div class="input-group" style="position: relative;">
                <div class="error-msg"><?= $data['errconfirm_password'] ?? '&nbsp;' ?></div>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password" style="padding-right: 40px;">
                <button type="button" id="togglePassword2" style="position: absolute; right: 25px; top: 15px; background: none; border: none; cursor: pointer;">
                    <i class="fa-regular fa-eye" id="eyeIcon2"></i>
                </button>
                
            </div>

            <button type="submit" id="updateBtn">Update Password</button>
            <a href="javascript:history.back()">Cancel</a>
        </form>
    </div>
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
</body>
</html>
