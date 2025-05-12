document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.querySelector(".overlay");
    const forgotBtn = document.querySelector(".pwd span");
    const signupBtn = document.querySelector(".create_one span");
    const loginBtn = document.querySelector("#show-login");
    const closeButtons = document.querySelectorAll(".close_btn p");
    const settings = document.querySelector('.settings');
    const profilePic = document.getElementById("profile-pic");
    const logout = document.getElementById('logout');
    const cancel_logout = document.getElementById('no_logout');
    const back_login = document.getElementById('back_login');

    //Modal for Settings Activation and Deactivation Loogic
    const open_settings_modal = document.getElementById('profile-pic');
    const settings_modal = document.querySelector('.settings_modal');

    open_settings_modal.addEventListener("click", () => {
        settings_modal.showModal();
    });

    //Settings Modal's Button Activator Variables
    const open_notif = document.getElementById('open_notif');
    const open_change_pass = document.getElementById('open_change_pass');
    const sample = document.getElementById('sample');
    const open_profile = document.getElementById('open_profile');
    const open_logout = document.getElementById('open_logout');

    //Settings Modals Dialog Variables
    const notifications_modal = document.querySelector('.notifications_modal');
    const change_pass_modal = document.querySelector('.change_pass_modal');
    const sample_modal = document.querySelector('.sample_modal');
    const profile_modal = document.querySelector('.profile_modal');
    const logout_confirm_modal = document.querySelector('.logout_confirm_modal');

    //Adding eventListener to Buttons to activate and deactivate modals
    open_notif.addEventListener("click", () => {
        notifications_modal.showModal();
    });

    open_change_pass.addEventListener("click", () => {
        change_pass_modal.showModal();
    });

    sample.addEventListener("click", () => {
        sample_modal.showModal();
    });

    open_profile.addEventListener("click", () => {
        profile_modal.showModal();
    });

    open_logout.addEventListener("click", () => {
        logout_confirm_modal.showModal();
    });

    no_logout.addEventListener("click", () => {
        logout_confirm_modal.close();
    });

    // List of all modal elements
    const modals = [
        notifications_modal,
        change_pass_modal,
        sample_modal,
        profile_modal,
        logout_confirm_modal,
        settings_modal
    ];

    // Add a common outside click handler to all modals
    modals.forEach(modal => {
        modal.addEventListener("click", (e) => {
            // If the user clicks directly on the backdrop (the <dialog> itself), close it
            if (e.target === modal) {
                modal.close();
            }
        });
    });

    // End of Settings Modal's Activation and DeactivationLogic 

    // Show login form
    loginBtn?.addEventListener("click", () => {
        showBox("login-form");
    });

    back_login?.addEventListener("click", () => {
        showBox("login-form");
    });

    forgotBtn?.addEventListener("click", () => {
        showBox("forgot-form");
    });

    signupBtn?.addEventListener("click", () => {
        showBox("signup-form");
    });

    // Close buttons (×)
    closeButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            closeAllForms();
        });
    });

    //profilePic?.addEventListener("click", () => {
    //    settings.classList.add("active");
    //});

    // Close when clicking outside the form
    window.addEventListener("click", (e) => {
        const activeForm = document.querySelector(".form.active");
    
        // Prevent closing if click was inside the form or a link to switch forms
        if (
            activeForm &&
            !activeForm.contains(e.target) &&
            !e.target.closest("#show-login") &&
            !e.target.closest("#back_login") &&
            !e.target.closest(".create_one") &&
            !e.target.closest(".pwd")
        ) {
            closeAllForms();
        }
    });

    window.addEventListener("click", (e) => {
        const settings = document.querySelector(".settings");
        const profilePic = document.getElementById("profile-pic");
    
        // Close settings only if:
        // - it's currently active
        // - and click is outside settings
        // - and click is not on the profile picture
        if (
            settings.classList.contains("active") &&
            !settings.contains(e.target) &&
            e.target !== profilePic
        ) {
            settings.classList.remove("active");
        }
    });
    

    // Show specific form
    window.showBox = function (id) {
        document.querySelectorAll(".form").forEach(form => {
            form.classList.add("hidden");
            form.classList.remove("active");
        });

        const target = document.getElementById(id);
        if (target) {
            target.classList.remove("hidden");
            target.classList.add("active");
            overlay.classList.add("active");
        }

    };

    // Close all forms
    function closeAllForms() {
        document.querySelectorAll(".form").forEach(form => {
            form.classList.remove("active");
            form.classList.add("hidden");
        });
        overlay.classList.remove("active");
    }

    window.handleLogin = function () {
        localStorage.setItem('isLoggedIn', 'true');
        updateAuthDisplay();
        closeAllForms();
    };

    logout?.addEventListener('click', function () {
        localStorage.setItem('isLoggedIn', 'false');
        updateAuthDisplay();
        settings.classList.remove("active");
        logout_confirm_modal.close();
        settings_modal.close();
    });

    // // Helper to update navbar buttons
    // function updateAuthDisplay() {
    //     const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    //     if (isLoggedIn) {
    //         profilePic.style.display = 'inline-block';
    //         loginBtn.style.display = 'none';
    //     } else {
    //         loginBtn.style.display = 'inline-block';
    //         profilePic.style.display = 'none';
    //     }
    // }

    // // Call on load
    // updateAuthDisplay();
});


const links = document.querySelectorAll("nav a");
const currentPage = window.location.pathname.split("/").pop(); // gets 'services.php'

links.forEach(link => {
    const linkPage = link.getAttribute("href").split("/").pop(); // gets 'services.php'
    if (linkPage === currentPage) {
        link.classList.add("active");
    } else {
        link.classList.remove("active");
    }
});


