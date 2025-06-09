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
            margin-bottom: 10px;
        }

        .error-msg {
            color: red;
            font-size: 0.85em;
            min-height: 1.2em;
            margin-top: 5px;
            margin-left: 40px;
        }

        input {
            display: block;
            width: 85%;
            padding: 10px;
            font-size: 1em;
        }

        button {
            padding: 10px 20px;
            font-size: 1em;
            cursor: pointer;
        }

        .create_one {
            display: block;
            font-size: 0.9em;
        }

        #eyeIcon {
            color: #555; 
            font-size: 1.2em;
        }

        #togglePassword {
            position: absolute; right: 10px; top: 35px; background: none; border: none; cursor: pointer;
        }

        @media (max-width: 600px) {
            .form {
                width: 90%;
                height: auto;
                padding: 20px 15px;
                transform: translate(-50%, -50%);
            }

            .form h2 {
                font-size: 2rem;
                margin: 0 0 20px 0;
            }

            .form input {
                margin: 10px 0;
                width: 100%;
                font-size: 1rem;
            }

            .form button {
                margin: 20px 0;
                width: 100%;
                font-size: 1rem;
            }

            .form .pwd,
            .form .create_one,
            .form a {
                margin: 10px 0;
                text-align: center;
                width: 100%;
                display: block;
            }

            .error-msg {
                margin-left: 0;
                font-size: 0.8em;
            }

            #togglePassword {
                top: 8px;
                right: 8px;
                text-align: right;
                width: fit-content;
            }

            #password {
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="form" id="login-form">
        <h2>LOGIN</h2>

        <?php if (!empty($data['loginerror'])): ?>
            <div class="error-msg"><?= $data['loginerror']; ?></div>
        <?php else: ?>
            <div class="error-msg">&nbsp;</div>
        <?php endif; ?>

        <form action="<?= baseurl() ?>/auth/signin" method="POST">

            <div class="input-group">
                <div class="error-msg"><?= $data['erremail'] ?? '&nbsp;' ?></div>
                <input 
                    type="text" 
                    name="email" 
                    placeholder="Enter email" 
                    value="<?= htmlspecialchars($emaildata ?? '') ?>"
                >
            </div>

            <div class="input-group" style="position: relative;">
                <div class="error-msg"><?= $data['errpassword'] ?? '&nbsp;' ?></div>
                <input type="password" name="password" id="password" placeholder="Enter password" style="padding-right: 40px;">
                <button type="button" id="togglePassword">
                    <i class="fa-regular fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            <a href="<?= baseurl() ?>/auth/forgotpassword" class="pwd"><span>Forgot Password?</span></a>
            <button type="submit">Login</button>
        </form>

        <a href="<?= baseurl() ?>/auth/signup" class="create_one">No Account? <span>Create one.</span></a>
        <a href="<?= baseurl() ?>/pages/home"><span>Back to Home</span></a>
    </div>
</body>
</html>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        // Toggle icon class
        if (type === 'password') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });
</script>
