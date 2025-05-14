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
                <h4>Name <span>*</span></h4>
                <div>
                    <p class="error"><?= $data['errfirstname'] ?? '&nbsp;' ?></p>
                    <label for="fname">First Name</label>
                    
                    <input type="text" name="fname" id="fname" value="<?= htmlspecialchars($userdata['fname'] ?? '') ?>">
                </div>

                <div>
                    <p class="error"><?= $data['errlastname'] ?? '&nbsp;' ?></p>
                     <label for="lname">Last Name</label>
                    
                    <input type="text" name="lname" id="lname" value="<?= htmlspecialchars($userdata['lname'] ?? '') ?>">
                </div>

                <div>
                    <label for="mname">Middle Name</label>
                    <input type="text" name="mname" id="mname" placeholder="(Optional)" value="<?= htmlspecialchars($userdata['mname'] ?? '') ?>">
                </div>
            </div>

            <div class="address">
                <h4>Address <span>*</span></h4>
                <div>
                    <p class="error"><?= $data['errbarangay'] ?? '&nbsp;' ?></p>
                    <label for="barangay">Barangay</label>
                    
                    <input type="text" name="barangay" id="barangay" value="<?= htmlspecialchars($userdata['barangay'] ?? '') ?>">
                </div>

                <div>
                    <p class="error"><?= $data['errmunicipality'] ?? '&nbsp;' ?></p>
                    <label for="municipality">Municipality</label>
                    
                    <input type="text" name="municipality" id="municipality" value="<?= htmlspecialchars($userdata['municipality'] ?? '') ?>">
                </div>

                <div>
                    <p class="error"><?= $data['errcity'] ?? '&nbsp;' ?></p>
                    <label for="city">City</label>
                    
                    <input type="text" name="city" id="city" value="<?= htmlspecialchars($userdata['city'] ?? '') ?>">
                </div>

                <div>
                    <p class="error"><?= $data['errpostal_code'] ?? '&nbsp;' ?></p>
                    <label for="postal_code">Postal / Zip Code</label>
                    
                    <input type="number" name="postal_code" id="postal_code" value="<?= htmlspecialchars($userdata['postal_code'] ?? '') ?>">
                </div>
            </div>

            <div class="phone_number">
                <h4>Phone Number <span>*</span></h4>
                <div>
                    <p class="error"><?= $data['errphone_number'] ?? '&nbsp;' ?></p>
                    <label for="phone_number">Phone Number</label>
    
                    <input type="tel" name="phone_number" id="phone_number" value="<?= htmlspecialchars($userdata['phone_number'] ?? '') ?>">
                </div>
            </div>

            <div class="blood_type">
                <h4>Blood Type <span>*</span></h4>
                <div>
                    <p class="error"><?= $data['errblood_type'] ?? '&nbsp;' ?></p>
                    <label for="blood_type">Blood Type</label>
                    
                    <select name="blood_type" id="blood_type" value="<?= htmlspecialchars($userdata['blood_type'] ?? '') ?>">
                        <option value="">Select Blood Type</option>
                        <option value="A+" <?= (isset($data['blood_type']) && $data['blood_type'] === 'A+') ? 'selected' : '' ?>>A+</option>
                        <option value="A-" <?= (isset($data['blood_type']) && $data['blood_type'] === 'A-') ? 'selected' : '' ?>>A-</option>
                        <option value="B+" <?= (isset($data['blood_type']) && $data['blood_type'] === 'B+') ? 'selected' : '' ?>>B+</option>
                        <option value="B-" <?= (isset($data['blood_type']) && $data['blood_type'] === 'B-') ? 'selected' : '' ?>>B-</option>
                        <option value="AB+" <?= (isset($data['blood_type']) && $data['blood_type'] === 'AB+') ? 'selected' : '' ?>>AB+</option>
                        <option value="AB-" <?= (isset($data['blood_type']) && $data['blood_type'] === 'AB-') ? 'selected' : '' ?>>AB-</option>
                        <option value="O+" <?= (isset($data['blood_type']) && $data['blood_type'] === 'O+') ? 'selected' : '' ?>>O+</option>
                        <option value="O-" <?= (isset($data['blood_type']) && $data['blood_type'] === 'O-') ? 'selected' : '' ?>>O-</option>
                        <option value="N/A" <?= (isset($data['blood_type']) && $data['blood_type'] === 'Forgotten') ? 'selected' : '' ?>>Forgotten</option>
                    </select>
                </div>
            </div>

            <div class="dob">
                <h4>Date of Birth <span>*</span></h4>
                <div>
                    <p class="error"><?= $data['errdob'] ?? '&nbsp;' ?></p>
                    <label for="dob">Date of Birth</label>
                    
                    <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($userdata['dob'] ?? '') ?>">
                </div>
            </div>

            <div class="pob">
                <h4>Place of Birth</h4>
                <div>
                    <p class="error"><?= $data['errpob'] ?? '&nbsp;' ?></p>
                    <label for="pob">Place of Birth</label>
                    
                    <input type="text" name="pob" id="pob" placeholder="(Optional)" value="<?= htmlspecialchars($userdata['pob'] ?? '') ?>">
                </div>
            </div>

            <div class="form-note">
                <p class="error"><?= $data['errconfirm'] ?? '&nbsp;' ?></p>
                <label>
                  <input type="checkbox" name="confirm" value="1" <?= (isset($data['confirm']) && $data['confirm'] === '1') ? 'checked' : '' ?>>
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