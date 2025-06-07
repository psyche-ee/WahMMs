<!DOCTYPE html>
<html>
<head>
    <title>Medical Chart</title>
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
        .form-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 30px 0;
            color: #333;
        }
        .section {
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            font-size: 18px;
            color: #D81616;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .subsection {
            margin-left: 20px;
            margin-bottom: 15px;
        }
        .subsection-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .content-line {
            margin-bottom: 8px;
        }
        .checkbox {
            width: 18px;
            height: 18px;
            border: 1px solid #333;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
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
        </div>
        
        <div class="form-title">MEDICAL CHART</div>
        
        <div class="section">
            <div class="section-title">Patient Information</div>
            <!-- Patient Information -->
            <p>
                Name: <span class="underline"><?= htmlspecialchars($patient['firstname'] . ' ' . $patient['lastname']) ?></span>
                Age: <span class="underline" style="min-width: 50px;">
                    <?= isset($patient['date_of_birth']) ? (date_diff(date_create($patient['date_of_birth']), date_create('today'))->y) : '' ?>
                </span>
                Sex: <span class="underline" style="min-width: 50px;"><?= htmlspecialchars($patient['gender']) ?></span>
            </p>
            <p>
                Date: <span class="underline"><?= isset($record['created_at']) ? date('F j, Y', strtotime($record['created_at'])) : date('F j, Y') ?></span>
                Chart No: <span class="underline" style="min-width: 100px;"><?= htmlspecialchars($record['medical_record_id'] ?? '') ?></span>
            </p>
        </div>
        
        <div class="section">
            <div class="section-title">Physical Examination</div>
            <div class="subsection">
                <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div><br><br>
                <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">Past Medical History</div>
            <div class="subsection">
                <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div><br><br>
                <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">Allergies/Medication History</div>
            <div class="subsection">
                <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div><br><br>
                <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            </div>
        </div>

        <div class="section">
        <div class="section-title">Social History</div>
        <div class="subsection">
            <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div><br><br>
            <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            <div class="content-line">
                <label><input type="checkbox" class="checkbox"> Tobacco Use</label>
                <label><input type="checkbox" class="checkbox"> Alcohol Use</label>
                <label><input type="checkbox" class="checkbox"> Drug Use</label>
            </div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Vital Signs</div>
        <div class="subsection">
            <div class="content-line">
                BP: <span class="underline" style="min-width: 80px;"><?= htmlspecialchars($record['blood_pressure'] ?? '') ?></span> mmHg
                HR: <span class="underline" style="min-width: 80px;"><?= htmlspecialchars($record['heart_rate'] ?? '') ?></span> bpm
                RR: <span class="underline" style="min-width: 80px;"></span> breaths/min
            </div>
            <div class="content-line">
                Temp: <span class="underline" style="min-width: 80px;"><?= htmlspecialchars($record['temperature'] ?? '') ?></span> °C
                SpO2: <span class="underline" style="min-width: 80px;"></span> %
                Weight: <span class="underline" style="min-width: 80px;"><?= htmlspecialchars($record['weight'] ?? '') ?></span> kg
            </div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Assessment & Plan</div>
        <div class="subsection">
            <div class="subsection-title">Primary Diagnosis:</div>
            <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            
            <div class="subsection-title">Secondary Diagnosis:</div>
            <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            
            <div class="subsection-title">Treatment Plan:</div>
            <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div><br><br>
            <div class="content-line"><span class="underline" style="min-width: 100%; display: block;"></span></div>
            
            <div class="content-line">
                <label><input type="checkbox" class="checkbox"> Follow-up in clinic</label>
                <label><input type="checkbox" class="checkbox"> Refer to specialist</label>
                <label><input type="checkbox" class="checkbox"> Laboratory tests needed</label>
            </div>
            <div class="content-line">
                <label><input type="checkbox" class="checkbox"> Follow IP</label>
                <label><input type="checkbox" class="checkbox"> Admit to hospital</label>
            </div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Medications Prescribed</div>
        <div class="subsection">
            <?php if (!empty($prescriptions)): ?>
                <?php foreach ($prescriptions as $i => $prescription): ?>
                    <div class="content-line">
                        <?= ($i+1) ?>. <span class="underline" style="min-width: 300px;"><?= htmlspecialchars($prescription['prescription_name']) ?></span>
                        Sig: <span class="underline" style="min-width: 200px;"><?= htmlspecialchars($prescription['dosage'] . ', ' . $prescription['frequency'] . ', ' . $prescription['duration']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="signature">
        <div class="signature-line"></div>
        <p>
            Physician: <span class="underline" style="min-width: 200px;"><?= htmlspecialchars($doctor_name) ?></span>
            License No: <span class="underline" style="min-width: 150px;"></span><br>
            Date: <span class="underline" style="min-width: 150px;"></span>
        </p>
    </div>

    <button class="print-btn" onclick="window.print()" style="margin: 30px auto 0 auto; display: block; background: #D81616; color: #fff; border: none; border-radius: 5px; padding: 12px 28px; font-size: 1em; font-weight: bold; cursor: pointer;">
        Print Medical Chart
    </button>
</div>