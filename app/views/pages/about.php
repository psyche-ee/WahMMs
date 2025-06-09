<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/All.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/About.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <?php require __DIR__ . '/../common/header.php'; ?>

    <main>
        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>Visit our clinic or reach out through our channels</p>
        </div>

        <div class="content-container">
            <div class="map-wrapper">
                <h2>Our Location</h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d296.2270428133914!2d123.9570967929022!3d10.258740049286361!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMTDCsDE1JzMxLjUiTiAxMjPCsDU3JzI1LjQiRQ!5e1!3m2!1sen!2sph!4v1749493631521!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <div class="right-column">
                <div class="clinic-info">
                    <h3>Clinic Information</h3>
                    
                    <div class="info-item">
                        <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong></p>
                        <p>Wahing Medical Clinic, Gabi, Cordoba, Cebu</p>
                    </div>
                    
                    <div class="info-item">
                        <p><i class="fa-brands fa-square-whatsapp"></i> <strong>Whatsapp:</strong></p>
                        <p>+63 927 304 6484</p>
                    </div>

                </div>

                <div class="social-section">
                    <h3>Connect With Us</h3>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/jsrunoflotante?mibextid=ZbWKwL" class="social-icon" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
<script src="<?= baseurl()?>/public/scripts/Wahing.js"></script>