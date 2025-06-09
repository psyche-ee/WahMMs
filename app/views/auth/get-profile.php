<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wahing Medical Clinic</title>
    <link rel="icon" type="image/png" href="<?= baseurl() ?>/public/assets/wahing_logo.png">
    <link rel="stylesheet" href="<?= baseurl() ?>/public/styles/get-profile.css">
</head>
<body>
    <main>
        <h1 class="sticky_header">New User Registration</h1>

        <form action="<?= baseurl() ?>/pages/home" method="POST">
            <!-- Name -->
            <div class="full_name">
                <h4>Full Name <span>*</span></h4>
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

            <!-- Address -->
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

            <!-- Phone -->
            <div class="phone_number">
                <h4>Phone Number <span>*</span></h4>
                <div>
                    <p class="error"><?= $data['errphone_number'] ?? '&nbsp;' ?></p>
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" value="<?= htmlspecialchars($userdata['phone_number'] ?? '') ?>">
                </div>
            </div>

            <!-- Blood Type -->
            <div class="blood_type">
                <h4>Blood Type <span>*</span></h4>
                <div>
                    <label for="blood_type">Select Blood Type</label>
                    <select name="blood_type" id="blood_type">
                        <option value="">-- Select --</option>
                        <?php
                        $blood_types = ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-", "N/A"];
                        foreach ($blood_types as $type):
                            $selected = (isset($data['blood_type']) && $data['blood_type'] === $type) ? 'selected' : '';
                        ?>
                            <option value="<?= $type ?>" <?= $selected ?>><?= $type ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- DOB -->
            <div class="dob">
                <h4>Date of Birth <span>*</span></h4>
                <div>
                    <p class="error"><?= $data['errdob'] ?? '&nbsp;' ?></p>
                    <label for="dob">Birth Date</label>
                    <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($userdata['dob'] ?? '') ?>">
                </div>
            </div>

            <!-- POB -->
            <div class="pob">
                <h4>Place of Birth</h4>
                <div>
                    <label for="pob">Place of Birth</label>
                    <input type="text" name="pob" id="pob" placeholder="(Optional)" value="<?= htmlspecialchars($userdata['pob'] ?? '') ?>">
                </div>
            </div>

            <!-- Confirmation -->
            <div class="form-note">
                <p class="error"><?= $data['errconfirm'] ?? '&nbsp;' ?></p>
                <label>
                    <input type="checkbox" name="confirm" value="1" <?= (isset($data['confirm']) && $data['confirm'] === '1') ? 'checked' : '' ?>>
                    I confirm that all information above is accurate.
                </label>
            </div>

            <!-- Submit + Back -->
            <div class="submit">
                <button type="submit">Submit</button>
                <div class="back-button">
                    <a href="<?= baseurl() ?>/auth/logout">
                        <button type="button">← Back</button>
                    </a>
                </div>

            </div>
        </form>
    </main>
</body>
</html>
