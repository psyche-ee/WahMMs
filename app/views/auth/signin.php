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
        <div class="close_btn" onclick="window.location.href='<?= baseurl() ?>';">
            <p>&times;</p>
        </div>
        <h2>Login</h2>

        <?php if (!empty($data['loginerror'])): ?>
            <div class="error-msg"><?= $data['loginerror']; ?></div>
        <?php else: ?>
            <div class="error-msg">&nbsp;</div>
        <?php endif; ?>

        <form action="<?= baseurl() ?>/auth/signin" method="POST">

            <div class="input-group">
                <input 
                    type="text" 
                    name="email" 
                    placeholder="Enter email" 
                    value="<?= htmlspecialchars($data['email'] ?? '') ?>"
                >
                <div class="error-msg"><?= $data['erremail'] ?? '&nbsp;' ?></div>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Enter password">
                <div class="error-msg"><?= $data['errpassword'] ?? '&nbsp;' ?></div>
            </div>

            <a href="<?= baseurl() ?>/auth/forgotpassword" class="pwd"><span>Forgot Password?</span></a>
            <button type="submit">Login</button>
        </form>

        <a href="<?= baseurl() ?>/auth/signup" class="create_one">No Account? <span>Create one.</span></a>
    </div>
</body>
</html>
