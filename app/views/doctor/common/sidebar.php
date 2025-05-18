<aside>
    <div class="logo">
        <img src="<?= baseurl()?>/public/assets/wahing_logo.png" alt="">
        <h1>WAHING</h1>
    </div>
    <div class="line"></div>
    <nav>
        <a href="<?= baseurl() ?>/pages/doctordashboard"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a>

       <!-- Appointments Dropdown -->
       <div class="appointments-toggle">
            <a href="#" id="appointmentsBtn">
                <i class="fa-solid fa-calendar-check"></i> <span>Appointments</span>
                <i class="fa-solid fa-chevron-down dropdown-icon"></i>
            </a>
            <div class="appointments-options" id="appointmentsOptions">
                <a href="<?= baseurl() ?>/pages/doctorappointments">All Appointments</a>
                <a href="<?= baseurl() ?>/pages/pendingappointments">Pending</a>
                <a href="<?= baseurl() ?>/pages/confirmedappointments">Confirmed</a>
                <a href="<?= baseurl() ?>/pages/declinedappointments">Cancelled</a>
                <a href="<?= baseurl() ?>/pages/completedappointments">Completed</a>
            </div>
        </div>

        <a href="<?= baseurl() ?>/pages/doctorpatients"><i class="fa-solid fa-user"></i> <span>Patients</span></a>

        <!-- Settings Toggle -->
        <div class="settings-toggle">
            <a href="#" id="settingsBtn">
            <i class="fa-solid fa-gear"></i> <span>Settings</span>
            <i class="fa-solid fa-chevron-down dropdown-icon"></i>
            </a>

            <!-- Collapsible Sub-menu -->
            <div class="settings-options" id="settingsOptions">
            <a href="<?= baseurl() ?>/pages/manageannouncements">Announcements</a>
            <a href="<?= baseurl() ?>/pages/manageavailability">Availability</a>
            <a href="<?= baseurl() ?>/pages/manageservices">Services</a>
            <a href="#">Comments</a>
            <a href="#">Change Password</a>
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