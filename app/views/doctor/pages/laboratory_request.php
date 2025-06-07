<!DOCTYPE html>
<html>
<head>
    <title>Laboratory Request Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #D81616;
            padding-bottom: 20px;
        }
        .clinic-name {
            font-size: 28px;
            font-weight: bold;
            color: #D81616;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .specialty {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .address {
            font-size: 16px;
            font-style: italic;
        }
        .form-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            text-decoration: underline;
            margin: 30px 0;
            color: #333;
        }
        .form-body {
            line-height: 1.8;
            font-size: 16px;
        }
        .underline {
            border-bottom: 1px solid #333;
            display: inline-block;
            min-width: 200px;
            padding-bottom: 3px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 250px;
            display: inline-block;
            margin-bottom: 5px;
        }
        .test-list {
            margin: 25px 0;
            column-count: 2;
            column-gap: 40px;
        }
        .test-item {
            margin-bottom: 15px;
            break-inside: avoid;
        }
        .checkbox {
            width: 18px;
            height: 18px;
            border: 1px solid #333;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }
        .urgent-stamp {
            float: right;
            border: 2px solid red;
            color: red;
            padding: 5px 10px;
            font-weight: bold;
            transform: rotate(15deg);
        }

        .styled-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #D81616; /* Modern browsers */
            margin-right: 10px;
            vertical-align: middle;
        }

        @media print {
            .print-btn { display: none; }
            body { background: #fff; }
            .cert-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header">
            <div class="clinic-name">DR.JESSIE MEDICAL CLINIC</div>
            <div class="specialty">FAMILY PHYSICIAN</div>
            <div class="address">GABI CORDOVA CEBU CITY | Tel: 0909-6360-S34</div>
        </div>
        
        <div class="form-title">LABORATORY REQUEST FORM</div>
        
        <div class="form-body">
            <div style="float: right;" class="urgent-stamp">URGENT</div>
            
            <p>
                Patient Name: <span class="underline"><?= htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']) ?></span>
                Age: <span class="underline" style="min-width: 50px;">
                    <?php
                        if (!empty($patient['date_of_birth'])) {
                            $dob = new DateTime($patient['date_of_birth']);
                            $today = new DateTime();
                            echo $today->diff($dob)->y;
                        }
                    ?>
                </span>
                Sex: <span class="underline" style="min-width: 50px;"><?= htmlspecialchars($patient['gender'] ?? '') ?></span>
            </p>
            <p>
                Address: <span class="underline" style="min-width: 400px;"><?= htmlspecialchars($patient['address'] ?? '') ?></span>
            </p>
            <p>
                Date: <span class="underline"><?= date('F j, Y') ?></span>
                Case No: <span class="underline"><?= htmlspecialchars($record['medical_record_id'] ?? '') ?></span>
            </p>
            
            <h3>REQUESTED LABORATORY TESTS:</h3>
            <div class="test-list">
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Complete Blood Count (CBC)</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Fasting Blood Sugar</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Lipid Profile</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Urinalysis</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Creatinine</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> SGPT/ALT</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> SGOT/AST</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> HBA1C</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Thyroid Function Tests</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Electrolytes</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Pregnancy Test</label></div>
                <div class="test-item"><label><input type="checkbox" class="styled-checkbox" /> Others: <span class="underline" style="min-width: 150px;"></span></label></div>
            </div>
            
            <p>Clinical Diagnosis: <br>
            <span class="underline" style="min-width: 100%; display: block;"></span></p>
            
            <p>Physician's Notes: <br>
            <span class="underline" style="min-width: 100%; display: block; min-height: 50px;"></span></p>
        </div>
        
        <div class="signature">
            <div class="signature-line"></div>
            <p>
                Requesting Physician: <span class="underline" style="min-width: 200px;"><?= htmlspecialchars($doctor_name ?? '') ?></span><br>
                License No: <span class="underline" style="min-width: 150px;"><?= htmlspecialchars($doctor_license ?? '') ?></span><br>
                Contact No: <span class="underline" style="min-width: 150px;"><?= htmlspecialchars($doctor_contact ?? '') ?></span>
            </p>
        </div>

        <button class="print-btn" onclick="window.print()" style="margin: 30px auto 0 auto; display: block; background: #D81616; color: #fff; border: none; border-radius: 5px; padding: 12px 28px; font-size: 1em; font-weight: bold; cursor: pointer;">
            <i class="fa fa-print"></i> Print Laboratory Request
        </button>
    </div>
</body>
</html>