<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/get-profile.css">
</head>
<body>
    <main>
        <h1 class="sticky_header">New User Registration Form</h1>

        <form action="<?= baseurl() ?>/pages/home" method="POST">
            <div class="full_name">
                <h4>Full Name <span>*</span></h4>
                <input type="text" name="fname" id="fname">
                <label for="fname">First Name</label>

                <input type="text" name="lname" id="lname">
                <label for="lname">Last Name</label>

                <input type="text" name="mname" id="mname" placeholder="(Optional)">
                <label for="mname">Middle Name</label>
            </div>

            <div class="address">
                <h4>Address <span>*</span></h4>
                <input type="text" name="barangay" id="barangay">
                <label for="barangay">Barangay</label>

                <input type="text" name="municipality" id="municipality">
                <label for="municipality">Municipality</label>

                <input type="text" name="city" id="city">
                <label for="city">City</label>

                <input type="number" name="postal_code" id="postal_code">
                <label for="postal_code">Postal / Zip Code</label>
            </div>

            <div class="phone_number">
                <h4>Phone Number <span>*</span></h4>
                <input type="tel" name="phone_number" required id="phone_number">
                <label for="phone_number">Phone Number</label>
            </div>

            <div class="blood_type">
                <h4>Blood Type <span>*</span></h4>
                <select name="blood_type" id="blood_type">
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="">Forgotten</option>
                </select>
                <label for="blood_type">Blood Type</label>
            </div>

            <div class="dob">
                <h4>Date of Birth <span>*</span></h4>
                <input type="date" name="dob" id="dob">
                <label for="dob">Date of Birth</label>
            </div>

            <div class="pob">
                <h4>Place of Birth</h4>
                <input type="text" name="pob" id="pob" placeholder="(Optional)">
                <label for="pob">Place of Birth</label>
            </div>

            <div class="form-note">
                <label>
                  <input type="checkbox" required>
                  I confirm that all the information provided above is true and correct.
                </label>
            </div>

            <div class="submit">
                <button type="submit">Submit</button>
            </div>
        </form>
    </main>
</body>
</html>