<!DOCTYPE html>
<html>
<head>
    <title>Prescription Form</title>
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
        .prescription-section {
            margin: 25px 0;
        }
        .prescription-item {
            margin-bottom: 15px;
        }
        .rx-symbol {
            font-size: 24px;
            font-weight: bold;
            margin-right: 10px;
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
                Monday & Tuesday: 6791-9794<br>
                Saturday & Sunday: 6791-9794<br>
                Gabi, Cholková, Chile<br><br>
                Thursday & Friday: 8794-9794<br>
                Cell No: (080) 6 860 534
            </div>
        </div>
        
        <div class="form-title">PRESCRIPTION</div>
        
        <div class="form-body">
            <p>
                Name: <span class="underline"><?= htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']) ?></span>
                Age: <span class="underline" style="min-width: 50px;">
                    <?= isset($patient['date_of_birth']) ? (date_diff(date_create($patient['date_of_birth']), date_create('today'))->y) : '' ?>
                </span>
                Sex: <span class="underline" style="min-width: 50px;"><?= htmlspecialchars($patient['gender']) ?></span>
            </p>

            <p>
                Address: <span class="underline" style="min-width: 400px;"><?= htmlspecialchars($patient['user_address']) ?></span>
            </p>

            <p>
                Date: <span class="underline"><?= date('F j, Y') ?></span>
            </p>

            <div class="prescription-section">
                <?php if (!empty($prescriptions)): ?>
                    <?php foreach ($prescriptions as $prescription): ?>
                        <div class="prescription-item">
                            <span class="rx-symbol">℞</span>
                            <span class="underline" style="min-width: 80%; display: inline-block;">
                                <?= htmlspecialchars($prescription['prescription_name']) ?>,
                                <?= htmlspecialchars($prescription['dosage']) ?>,
                                <?= htmlspecialchars($prescription['frequency']) ?>,
                                <?= htmlspecialchars($prescription['duration']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="prescription-item">
                        <span class="rx-symbol">℞</span>
                        <span class="underline" style="min-width: 80%; display: inline-block;"></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="signature">
            <div class="signature-line"></div>
            <p>
                Jessieneth Stephen F. Wahing, MD<br>
                License No: 0156312
            </p>
        </div>

        <button class="print-btn" onclick="window.print()" style="margin: 30px auto 0 auto; display: block; background: #D81616; color: #fff; border: none; border-radius: 5px; padding: 12px 28px; font-size: 1em; font-weight: bold; cursor: pointer;">
            Print Prescription
        </button>
    </div>
</body>
</html>