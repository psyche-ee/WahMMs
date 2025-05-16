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
            margin-top: 10px;
        }

        .success-msg {
            color: green;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form" id="forgot-form">
        
        <h2>FORGOT PASSWORD</h2>

        <?php if (Session::get('success')): ?>
            <p class="success-msg"><?= Session::get('success') ?></p>
            <script>
                setTimeout(() => {
                    const msg = document.querySelector('.success-msg');
                    if (msg) msg.style.display = 'none';
                    window.location.href = "<?= baseurl() ?>/pages/home";
                }, 5000);
            
            </script>
            <?php Session::remove('success'); ?>
        <?php else: ?>
            <p class="success-msg">&nbsp;</p>
        <?php endif; ?>

        <div class="error-msg"><?= $data['erremail'] ?? '&nbsp;' ?></div>

        <form action="<?= baseurl() ?>/auth/forgotpassword" method="POST">
            <div class="input-group">
                <input 
                    type="text" 
                    name="email" 
                    placeholder="Enter email"
                    value="<?= htmlspecialchars($userdata['email'] ?? '') ?>"
                >
            </div>
            <button type="submit">Send Reset Link</button>
        </form>

        <form action="<?= baseurl() ?>/pages/signin" method="POST">
            <button id="back_login">Back to Login Page</button>
        </form>
    </div>
</body>
</html>
