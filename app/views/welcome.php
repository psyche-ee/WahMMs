<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= baseurl()?>/public/assets/style/welcome.css">
    <title>Document</title>
</head>
<body>
    <?php 
    if (!Session::getIsLoggedIn()) : ?>
        <h1>Complete Auth System with Session and Cookie</h1>
    <?php else: ?>
        <?php $info = $this->usermodel->getProfileInfo(Session::getUserId()); ?>
        <h1>Welcome <?=  htmlspecialchars($_SESSION['name']) ?></h1>
    <?php endif; ?>
    <form action="<?= baseurl() ?>/auth/logout" method="post">
    <button type="submit">Logout</button>
</form>
</body>
</html>