<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl()?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/All.css">
    <link rel="stylesheet" href="<?= baseurl()?>/public/styles/edit_profile.css">
</head>
<body>
    <main>
        <h1 class="sticky_header">Edit Profile</h1>

        <form action="<?= baseurl() ?>/pages/edit_profile" method="POST">
            <div class="form-columns">
                <!-- Left Column -->
                <div class="left-column">
                    <div class="full_name">
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

                        <div>
                            <label for="mname">Gender</label>
                            <select name="gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="address">
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
                </div>

                <!-- Right Column -->
                <div class="right-column">
                    <div class="phone_number">
                        <p class="error"><?= $data['errphone_number'] ?? '&nbsp;' ?></p>
                        <label for="phone_number">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number" value="<?= htmlspecialchars($userdata['phone_number'] ?? '') ?>">
                    </div>

                    <div class="blood_type">
                        <p class="error"><?= $data['errblood_type'] ?? '&nbsp;' ?></p>
                        <label for="blood_type">Blood Type</label>
                        <select name="blood_type" id="blood_type">
                            <option value="">Select Blood Type</option>
                            <?php
                                $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'N/A'];
                                foreach ($bloodTypes as $type) {
                                    $selected = (isset($data['blood_type']) && $data['blood_type'] === $type) ? 'selected' : '';
                                    echo "<option value=\"$type\" $selected>$type</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="dob">
                        <p class="error"><?= $data['errdob'] ?? '&nbsp;' ?></p>
                        <label for="dob">Date of Birth</label>
                        <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($userdata['dob'] ?? '') ?>">
                    </div>

                    <div class="pob">
                        <p class="error"><?= $data['errpob'] ?? '&nbsp;' ?></p>
                        <label for="pob">Place of Birth</label>
                        <input type="text" name="pob" id="pob" placeholder="(Optional)" value="<?= htmlspecialchars($userdata['pob'] ?? '') ?>">
                    </div>
                </div>
            </div>

            <div class="form_note">
                <p class="error"><?= $data['errconfirm'] ?? '&nbsp;' ?></p>
                <label>
                    <input type="checkbox" name="confirm" value="1" <?= (isset($data['confirm']) && $data['confirm'] === '1') ? 'checked' : '' ?>>
                    I confirm that all the information provided above is true and correct.
                </label>
            </div>

            <div class="submit">
                <button type="submit">Save</button>
                <a href="<?= baseurl()?>/pages/home"><button type="button">Cancel</button></a>
            </div>
        </form>
    </main>
</body>
</html>