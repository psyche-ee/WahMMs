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
                <button type="button" id="togglePassword" style="position: absolute; right: 30px; top: 35px; background: none; border: none; cursor: pointer;">
                    👁️
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
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        // Optionally change the icon/text
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });
</script>
