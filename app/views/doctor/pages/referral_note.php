<!DOCTYPE html>
<html>
<head>
    <title>Referral Note</title>
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
        .hours-address {
            font-size: 16px;
            margin-bottom: 10px;
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
        .diagnosis-section, .reasons-section {
            margin: 25px 0;
        }
        .diagnosis-item, .reason-item {
            margin-bottom: 15px;
        }
        @media print {
            .print-btn { display: none; }
            body { background: #fff; }
            .form-container { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header">
            <div class="clinic-name">DR. JESSIE MEDICAL CLINIC</div>
            <div class="specialty">FAMILY PHYSICIAN</div>
            <div class="hours-address">
                Monday & Tuesday: 6PM - 9PM<br>
                Saturday & Sunday: 6PM - 9PM<br>
                Gabi, Cordova, Cebu<br><br>
                Thursday & Friday: 6PM - 9PM<br>
                Cell No: 0909-6360-534
            </div>
        </div>
        
        <div class="form-title">REFERRAL NOTE</div>
        
        <div class="form-body">
            <p>
                Date: <span class="underline"><?= date('F j, Y') ?></span>
            </p>

            <p>
                Name: <span class="underline"><?= htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']) ?></span>
                Age: <span class="underline" style="min-width: 50px;"><?= isset($patient['date_of_birth']) ? (date_diff(date_create($patient['date_of_birth']), date_create('today'))->y) : '' ?></span>
                Sex: <span class="underline" style="min-width: 50px;"><?= htmlspecialchars($patient['gender']) ?></span>
            </p>

            <p>
                Address: <span class="underline" style="min-width: 400px;"><?= htmlspecialchars($patient['user_address']) ?></span>
            </p>

            <p>
                Date of birth: <span class="underline"><?= htmlspecialchars($patient['date_of_birth']) ?></span>
            </p>

            <p>
                To: <span class="underline" style="min-width: 400px;"></span>
            </p>

            <p>
                Respectfully referring to you Mr/Mrs/Ms <span class="underline"><?= htmlspecialchars($patient['lastname']) ?></span>
            </p>

            <p>
                He/She was seen/examined on <span class="underline"><?= isset($record['created_at']) ? date('F j, Y', strtotime($record['created_at'])) : '' ?></span> due to <span class="underline"><?= htmlspecialchars($record['diagnostic'] ?? '') ?></span>
            </p>

            <div class="diagnosis-section">
                <p>And with the following diagnosis:</p>
                <div class="diagnosis-item"><span class="underline" style="min-width: 100%; display: block;"><?= htmlspecialchars($record['diagnostic'] ?? '') ?></span></div>
                <div class="diagnosis-item"><span class="underline" style="min-width: 100%; display: block;"></span></div>
                <div class="diagnosis-item"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            </div>

            <div class="reasons-section">
                <p>Reasons for referral:</p>
                <div class="reason-item"><span class="underline" style="min-width: 100%; display: block;"></span></div>
                <div class="reason-item"><span class="underline" style="min-width: 100%; display: block;"></span></div>
                <div class="reason-item"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            </div>
            
            <p style="margin-top: 40px;">Thank you & God bless...</p>
        </div>
        
        <div class="signature">
            <div class="signature-line"></div>
            <p>
                Jessieneth Stephen F. Wahing, MD<br>
                License No: 0156312
            </p>
        </div>

        <button class="print-btn" onclick="window.print()" style="margin: 30px auto 0 auto; display: block; background: #D81616; color: #fff; border: none; border-radius: 5px; padding: 12px 28px; font-size: 1em; font-weight: bold; cursor: pointer;">
            Print Referral Note
        </button>
    </div>
</body>
</html>