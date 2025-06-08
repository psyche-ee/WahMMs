<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Required | Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <style>
        :root {
            --primary: #D81616;
            --primary-light: #f8e8e8;
            --text: #333333;
            --text-light: #666666;
            --border: #e0e0e0;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text);
        }
        
        .verification-container {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            position: relative;
        }
        
        .verification-header {
            background-color: var(--primary);
            color: white;
            padding: 24px;
            text-align: center;
        }
        
        .verification-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .verification-body {
            padding: 32px;
        }
        
        .verification-icon {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .verification-icon svg {
            width: 64px;
            height: 64px;
            fill: var(--primary);
        }
        
        .verification-message {
            margin-bottom: 24px;
            line-height: 1.6;
            text-align: center;
        }
        
        .verification-message p {
            margin: 8px 0;
            color: var(--text-light);
        }
        
        .verification-message strong {
            color: var(--text);
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-size: 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            margin-bottom: 16px;
        }
        
        .btn-primary:hover {
            background-color: #c01212;
        }
        
        .btn-secondary {
            background-color: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-light);
        }
        
        .close-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            font-size: 1.5rem;
            line-height: 1;
            z-index: 10;
        }
        
        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="close-btn" onclick="window.location.href='<?= baseurl() ?>';">
            &times;
        </div>
        
        <div class="verification-header">
            <h2>Verify Your Email</h2>
        </div>
        
        <div class="verification-body">
            <div class="verification-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z"/>
                </svg>
            </div>
            
            <div class="verification-message">
                <p><strong>Your email address is not yet verified.</strong></p>
                <p>We've sent a verification link to your email address.</p>
                <p>Please check your inbox and click the link to complete your registration.</p>
                <?php if(isset($_SESSION['verify_notice'])): ?>
                    <p class="notice"><?= $_SESSION['verify_notice'] ?></p>
                <?php endif; ?>
            </div>
            
            <form method="post" action="<?= baseurl() ?>/auth/resendverification">
                <button type="submit" class="btn btn-primary">Resend Verification Email</button>
            </form>
            
            <form action="<?= baseurl() ?>/pages/home" method="POST">
                <button type="submit" class="btn btn-secondary">Back to Login Page</button>
            </form>
        </div>
    </div>
</body>
</html>