     
    <div class="overlay"></div>
    
    <header>
        <div class="logo">
            <img src="<?= baseurl()?>/public/assets/wahing_logo.png" alt="Logo">
            <h1>WAHING</h1>
        </div>
        <nav>
            <a href="<?= baseurl() ?>/pages/home">Home</a>
            <a href="<?= baseurl() ?>/pages/services">Services</a>
            <a href="<?= baseurl() ?>/pages/about">About</a>
            <a href="<?= baseurl() ?>/pages/announcements">Announcements</a>
            <div class="auth_control">
                <form action="<?= baseurl() ?>/pages/signin" method="POST">
                    <button class="loginbtn" id="show-login" style="<?= isset($_SESSION['name']) ? 'display: none;' : 'display: inline-block;' ?>">Login</button>
                </form>
                
                <img src="<?= baseurl()?>/public/assets/profile_pic.jpg" alt="Profile" id="profile-pic" class="profile" style="<?= isset($_SESSION['name']) ? 'display: inline-block;' : 'display: none;' ?>">
            </div>
        </nav>
        <div class="hamburger"><i class="fa-solid fa-bars"></i></div>
    </header>

    <dialog class="settings_modal">
        <div class="pp">
            <img src="<?= baseurl() ?>/public/assets/profile_pic.jpg" alt="">
            <?php if(isset($_SESSION['name'])): ?>
                <h3 class="name"><?= htmlspecialchars($_SESSION['name']) ?></h3>
            <?php endif; ?>

            <?php if(isset($_SESSION['email'])): ?>
                <p class="email"><?= htmlspecialchars($_SESSION['email']) ?></p>
            <?php endif; ?>
        </div>
        <div class="settingsline"></div>
        <p id="open_change_pass"><i class="fa-regular fa-pen-to-square"></i>Change Password</p>
        <p id="open_profile"><i class="fa-regular fa-user"></i>Profile</p>
        <p id="open_logout"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</p>
    </dialog>

    <dialog class="change_pass_modal">
        <h1>Change Password</h1>
        <form action="<?= baseurl() ?>/auth/changepassword" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <label for="password">New Password</label>
            <div class="password-field">
                <input type="password" id="password" name="password" placeholder="New Password" required style="width: 100%; padding-right: 40px;">
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)"><i class="fa-regular fa-eye"></i></button>
            </div>

            <label for="confirm_password">Confirm Password</label>
            <div class="password-field">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required style="width: 100%; padding-right: 40px;">
                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)"><i class="fa-regular fa-eye"></i></button>
            </div>
            <div class="save_cancel_cntnr">
                <button class="submit_btn" type="submit">Save</button>
                <button class="cancel_btn" onclick="window.location.href='<?= baseurl() ?>/pages/home';">Cancel</button>
            </div>
        </form>
    </dialog>

    <dialog class="profile_modal">
        <button>Back</button>
        <h1>Profile</h1>
    </dialog>

    <dialog class="logout_confirm_modal">
        <h1>Are you sure you want to logout?</h1>
        <div class="logout_button_container">
            <form action="<?= baseurl() ?>/auth/logout" method="post">
                <button type="submit" id="logout">Yes</button>
            </form>
            <button id="no_logout">No</button>
        </div>
    </dialog>

    <script src="<?= baseurl()?>/public/scripts/Wahing.js"></script>
    <script>
        // Handle Burger Settings Menu
        const hamburger = document.querySelector('.hamburger');
        const nav = document.querySelector('nav');

        hamburger.addEventListener('click', () => {
            nav.classList.toggle('active');
        });

        function togglePassword(fieldId, toggleBtn) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
            toggleBtn.innerHTML = '<i class="fa-regular fa-eye-slash"></i>'; // Closed eye / hidden
        } else {
            input.type = "password";
            toggleBtn.innerHTML = '<i class="fa-regular fa-eye"></i>'; // Open eye / visible
        }
    }
    </script>