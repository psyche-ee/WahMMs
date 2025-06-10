     
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
        <a href="<?= baseurl() ?>/auth/changepassword"><p id="open_change_pass"><i class="fa-regular fa-pen-to-square"></i>Change Password</p></a>
        <p id="open_profile"><i class="fa-regular fa-user"></i>Profile</p>
        <?php if (!empty($medicalHistory)): ?>
    <p onclick="openMedicalHistoryModal()" style="cursor:pointer;">
        <i class="fa-solid fa-notes-medical"></i>Medical History
    </p>
<?php else: ?>
    <p onclick="openMedicalHistoryModal()" style="cursor:pointer;">
    <i class="fa-solid fa-notes-medical"></i>Medical History
</p>
<?php endif; ?>
        <p id="open_logout"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</p>
    </dialog>

    <!-- <dialog class="change_pass_modal">
        <h1>Change Password</h1>
        <form action="<?= baseurl() ?>/auth/changepassword" method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">

            <label for="password">New Password</label>
            <div class="password-field">
                <input type="password" id="password" name="password" placeholder="New Password" style="width: 100%; padding-right: 40px;">
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)"><i class="fa-regular fa-eye"></i></button>
            </div>

            <label for="confirm_password">Confirm Password</label>
            <div class="password-field">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" style="width: 100%; padding-right: 40px;">
                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)"><i class="fa-regular fa-eye"></i></button>
            </div>
            <div class="save_cancel_cntnr">
                <button class="submit_btn" type="submit">Save</button>
                <button class="cancel_btn" onclick="window.location.href='<?= baseurl() ?>/pages/home';">Cancel</button>
            </div>
        </form>
    </dialog> -->

    <!-- <dialog id="confirmation-modal">
        <div>
            <p id="confirmation-message"></p>
            <button class="ok" onclick="document.getElementById('confirmation-modal').close()">OK</button>
        </div>
    </dialog> -->

    <dialog class="profile_modal">
        <h1>Profile</h1>
        <div class="profile_container">
            <img src="<?= baseurl() ?>/public/assets/profile_pic.jpg" alt="">
            <div class="userInfo">
                <h4>Name: <span><?= htmlspecialchars($userInfo['firstname'] . ' ' . $userInfo['middlename'] . ' ' . $userInfo['lastname']) ?></span></h4>
                <h4>Email: <span><?= htmlspecialchars($userInfo['email']) ?></span></h4>
                <h4>Phone: <span><?= htmlspecialchars($userInfo['phone_number']) ?></span></h4>
                <h4>Blood Type: <span><?= htmlspecialchars($userInfo['blood_type']) ?></span></h4>
                <h4>Gender: <span><?= htmlspecialchars($userInfo['gender']) ?></span></h4>
                <h4>Age: 
                    <span>
                            <?php
                                $dob = new DateTime($userInfo['date_of_birth']);
                                $now = new DateTime();
                                $age = $now->diff($dob)->y;
                                echo htmlspecialchars($age);
                            ?>
                    </span>
                </h4>
                <h4>Address: <span><?= htmlspecialchars($userInfo['user_address']) ?></span></h4>
                <h4>Date Created: <span> 
                    <?php
                        $createdAt = new DateTime($userInfo['created_at']);
                        echo htmlspecialchars($createdAt->format('F j, Y')); // e.g., May 15, 2025
                    ?>
            </div>

        </div>
        <a href="<?= baseurl() ?>/pages/edit_profile">Edit</a>
    </dialog>

    <dialog class="logout_confirm_modal">
        <h1>Are you sure you want to logout?</h1>
        <div class="logout_button_container">
            <form action="<?= baseurl() ?>/auth/logout" method="POST">
                <button type="submit" id="logout">Yes</button>
            </form>
            <button id="no_logout">No</button>
        </div>
    </dialog>

    <?php if (isset($medicalHistory) && !empty($medicalHistory)): ?>
        <dialog class="big_modal" id="medicalHistoryModal">
            <div class="modal_content">
                <div class="modal_header">
                    <h2>Medical History</h2>
                    <button id="closeMedicalHistory" class="close_btn">&times;</button>
                </div>
                <div class="modal_body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Service</th>
                                    <th>BP</th>
                                    <th>HR</th>
                                    <th>Temp</th>
                                    <th>Immunization</th>
                                    <th>Follow-up</th>
                                    <th>Diagnostic</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($medicalHistory as $record): ?>
                                    <tr>
                                        <td data-label="Date">
                                            <?= (new DateTime($record['created_at'], new DateTimeZone('UTC')))
                                                    ->setTimezone(new DateTimeZone('Asia/Manila'))
                                                    ->format('Y-m-d') ?>
                                        </td>
                                        <td data-label="Service"><?= htmlspecialchars($record['service_name']) ?></td>
                                        <td data-label="BP"><?= htmlspecialchars($record['blood_pressure']) ?></td>
                                        <td data-label="HR"><?= htmlspecialchars($record['heart_rate']) ?></td>
                                        <td data-label="Temp"><?= htmlspecialchars($record['temperature']) ?></td>
                                        <td data-label="Immunization"><?= htmlspecialchars($record['immunization_status']) ?></td>
                                       
                                        <td data-label="Follow-up">
                                            <?php
                                                if (!empty($record['follow_up_date'])) {
                                                    echo (new DateTime($record['follow_up_date'], new DateTimeZone('UTC')))
                                                        ->setTimezone(new DateTimeZone('Asia/Manila'))
                                                        ->format('M d, Y');
                                                } else {
                                                    echo '—';
                                                }
                                            ?>
                                        </td>
                                         <td data-label="Diagnostic"><?= htmlspecialchars($record['diagnostic']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </dialog>
    <?php else: ?>
        <dialog class="big_modal" id="medicalHistoryModal">
            <div class="modal_content">
                <div class="modal_header">
                    <h2>Medical History</h2>
                    <button id="closeMedicalHistory" class="close_btn">&times;</button>
                </div>
                <div class="modal_body">
                    <p>No medical history records found.</p>
                </div>
            </div>
        </dialog>
    <?php endif; ?>

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

        function openMedicalHistoryModal() {
        const modal = document.getElementById('medicalHistoryModal');
        if (modal) {
            modal.showModal();  // this opens a <dialog>
        }
    }

    // Also handle close button
    document.addEventListener("DOMContentLoaded", () => {
        const closeBtn = document.getElementById("closeMedicalHistory");
        const modal = document.getElementById("medicalHistoryModal");
        if (closeBtn && modal) {
            closeBtn.addEventListener("click", () => {
                modal.close();
            });
        }
    });
    </script>