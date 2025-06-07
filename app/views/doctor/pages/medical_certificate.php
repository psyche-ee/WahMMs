<!DOCTYPE html>
<html>
<head>
    <title>Medical Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
            padding: 20px;
        }
        .cert-container {
            border: 2px solid #D81616;
            background: #fff;
            border-radius: 10px;
            max-width: 700px;
            margin: 20px auto;
            padding: 40px;
            box-shadow: 0 4px 16px rgba(216,22,22,0.07);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #D81616;
            padding-bottom: 15px;
        }
        .clinic-name {
            font-weight: bold;
            color: #D81616;
            font-size: 1.3em;
            line-height: 1.3;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .specialty {
            font-size: 1em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .address {
            font-size: 0.9em;
            font-style: italic;
        }
        .cert-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            font-size: 1.3em;
            color: #D81616;
        }
        .cert-content {
            font-size: 1em;
            color: #222;
            line-height: 1.6;
        }
        .cert-label {
            display: inline-block;
            min-width: 90px;
            font-weight: bold;
        }
        .underline {
            border-bottom: 1px solid #333;
            display: inline-block;
            min-width: 120px;
            padding: 0 4px;
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
        .print-btn {
            margin-top: 30px;
            padding: 12px 28px;
            background: #D81616;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        @media print {
            .print-btn { display: none; }
            body { background: #fff; }
            .cert-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="header">
            <div class="clinic-name">DR. JESSIE MEDICAL CLINIC</div>
            <div class="specialty">FAMILY PHYSICIAN</div>
            <div class="address">GABI CORDOVA CEBU CITY | Tel: 0909-6360-S34</div>
        </div>
        
        <div class="cert-title">MEDICAL CERTIFICATE</div>
        
        <div class="cert-content">
            <p>
                <span class="cert-label">Patient name:</span>
                <span class="underline"><?= htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']) ?></span>
                <span class="cert-label">Age:</span>
                <span class="underline" style="min-width: 40px;">
                    <?php
                        if (!empty($patient['date_of_birth'])) {
                            $dob = new DateTime($patient['date_of_birth']);
                            $today = new DateTime();
                            echo $today->diff($dob)->y;
                        } else {
                            echo "&nbsp;";
                        }
                    ?>
                </span>
                <span class="cert-label">Sex:</span>
                <span class="underline" style="min-width: 50px;"><?= htmlspecialchars($patient['gender'] ?? '') ?></span>
            </p>
            <p>
                <span class="cert-label">Address:</span>
                <span class="underline" style="min-width: 400px;"><?= htmlspecialchars($patient['address'] ?? '') ?></span>
            </p>
            <p>
                <span class="cert-label">Date:</span>
                <span class="underline"><?= date('F j, Y') ?></span>
                <span class="cert-label">Case No:</span>
                <span class="underline"><?= htmlspecialchars($record['medical_record_id'] ?? '') ?></span>
            </p>
            
            <p>This is to certify that the above mentioned patient was examined on 
            <span class="underline"><?= !empty($record['created_at']) ? date('F j, Y', strtotime($record['created_at'])) : date('F j, Y') ?></span>
            with the following findings:</p>
            
            <p><span class="cert-label">Diagnosis:</span><br>
            <span class="underline" style="min-width: 100%; display: block;"><?= htmlspecialchars($diagnosis) ?></span></p>
            
            <p><span class="cert-label">Clinical Impression:</span><br>
            <span class="underline" style="min-width: 100%; display: block;"><?= htmlspecialchars($diagnosis) ?></span></p>
            
            <p><span class="cert-label">Recommendations:</span><br>
            <span class="underline" style="min-width: 100%; display: block;">
                <?php if (!empty($days)): ?>
                    Advised to rest for <?= htmlspecialchars($days) ?> day(s)
                    
                <?php endif; ?>
            </span></p>
            
            <p>This certificate was issued upon request of the patient for whatever legal purpose it may serve.</p>
        </div>
        
        <div class="signature">
            <div class="signature-line"></div>
            <p>
                <span class="cert-label">Attending Physician:</span>
                <span class="underline" style="min-width: 200px;"><?= htmlspecialchars($doctor_name ?? '') ?></span><br>
                <span class="cert-label">License No:</span>
                <span class="underline" style="min-width: 150px;">0156312</span><br>
                <span class="cert-label">Contact No:</span>
                <span class="underline" style="min-width: 150px;">0909-6360-S34</span>
            </p>
        </div>

        <button class="print-btn" onclick="window.print()">
            Print Certificate
        </button>
    </div>
</body>
</html>