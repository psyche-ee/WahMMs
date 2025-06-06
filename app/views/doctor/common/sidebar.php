<?php
$current = $_SERVER['REQUEST_URI'];
function isActive($path) {
    return strpos($_SERVER['REQUEST_URI'], $path) !== false ? 'active' : '';
}
$settingsActive = isActive('/pages/manageannouncements') ||
                  isActive('/pages/manageavailability') ||
                  isActive('/pages/manageservices') ||
                  isActive('/pages/managecomments') ||
                  isActive('/pages/changepassword');
?>

<aside>
    <div class="logo">
        <img src="<?= baseurl()?>/public/assets/wahing_logo.png" alt="">
        <h1>WAHING</h1>
    </div>
    <div class="line"></div>
    <nav>
        <a href="<?= baseurl() ?>/pages/doctordashboard" class="<?= isActive('/pages/doctordashboard') ?>"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a>

       <!-- Appointments Dropdown -->
       <div class="appointments-toggle<?= isActive('/pages/doctorappointments') || isActive('/pages/pendingappointments') || isActive('/pages/confirmedappointments') || isActive('/pages/declinedappointments') || isActive('/pages/completedappointments') ? ' open' : '' ?>">
            <a href="#" id="appointmentsBtn">
                <i class="fa-solid fa-calendar-check"></i> <span>Appointments</span>
                <i class="fa-solid fa-chevron-down dropdown-icon"></i>
            </a>
            <div class="appointments-options" id="appointmentsOptions" style="display: <?= (isActive('/pages/doctorappointments') || isActive('/pages/pendingappointments') || isActive('/pages/confirmedappointments') || isActive('/pages/declinedappointments') || isActive('/pages/completedappointments')) ? 'flex' : 'none' ?>;">
                <a href="<?= baseurl() ?>/pages/doctorappointments" class="<?= isActive('/pages/doctorappointments') ?>">All Appointments</a>
                <a href="<?= baseurl() ?>/pages/pendingappointments" class="<?= isActive('/pages/pendingappointments') ?>">Pending</a>
                <a href="<?= baseurl() ?>/pages/confirmedappointments" class="<?= isActive('/pages/confirmedappointments') ?>">Confirmed</a>
                <a href="<?= baseurl() ?>/pages/declinedappointments" class="<?= isActive('/pages/declinedappointments') ?>">Cancelled</a>
                <a href="<?= baseurl() ?>/pages/completedappointments" class="<?= isActive('/pages/completedappointments') ?>">Completed</a>
            </div>
        </div>

        <a href="<?= baseurl() ?>/pages/doctorpatients"><i class="fa-solid fa-user"></i> <span>Patients</span></a>

        <!-- Settings Toggle -->
        <div class="settings-toggle<?= $settingsActive ? ' open' : '' ?>">
            <a href="#" id="settingsBtn">
                <i class="fa-solid fa-gear"></i> <span>Settings</span>
                <i class="fa-solid fa-chevron-down dropdown-icon"></i>
            </a>

            <!-- Collapsible Sub-menu -->
            <div class="settings-options" id="settingsOptions" style="display: <?= $settingsActive ? 'flex' : 'none' ?>;">
                <a href="<?= baseurl() ?>/pages/manageannouncements" class="<?= isActive('/pages/manageannouncements') ?>">Announcements</a>
                <a href="<?= baseurl() ?>/pages/manageavailability" class="<?= isActive('/pages/manageavailability') ?>">Availability</a>
                <a href="<?= baseurl() ?>/pages/manageservices" class="<?= isActive('/pages/manageservices') ?>">Services</a>
                <a href="#" class="<?= isActive('/pages/managecomments') ?>">Comments</a>
                <a href="<?= baseurl() ?>/auth/changepassword" class="<?= isActive('/auth/changepassword') ?>">Change Password</a>
            </div>
        </div>
        <a href="<?= baseurl() ?>/pages/home"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a>
    </nav>
</aside>

<script>
    const settingsBtn = document.getElementById('settingsBtn');
    const settingsOptions = document.getElementById('settingsOptions');
    const settingsToggle = document.querySelector('.settings-toggle');

    settingsBtn.addEventListener('click', (e) => {
    e.preventDefault(); // Prevent link jump
    settingsOptions.style.display = settingsOptions.style.display === 'flex' ? 'none' : 'flex';
    settingsToggle.classList.toggle('open');
    });

    // Appointments dropdown
    const appointmentsBtn = document.getElementById('appointmentsBtn');
    const appointmentsOptions = document.getElementById('appointmentsOptions');
    const appointmentsToggle = document.querySelector('.appointments-toggle');

    appointmentsBtn.addEventListener('click', (e) => {
        e.preventDefault();
        appointmentsOptions.style.display = appointmentsOptions.style.display === 'flex' ? 'none' : 'flex';
        appointmentsToggle.classList.toggle('open');
    });
</script>