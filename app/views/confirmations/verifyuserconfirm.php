<style>
   body { 
      font-family: Arial, sans-serif; 
      background: #f7f7f7; 
      margin:0; 
      padding:0;
      text-align: center;
   }
   .container { 
      max-width:480px; 
      margin:80px auto; 
      background:#fff; 
      border-radius:8px; 
      box-shadow:0 2px 8px rgba(0,0,0,0.07); 
      border:1px solid #eee;
      overflow: hidden;
   }
   .header { 
      background:#D81616; 
      color:#fff; 
      padding:24px 32px; 
      text-align:center; 
   }
   .header h1 { 
      margin:0; 
      font-size:1.6rem; 
      letter-spacing:1px; 
   }
   .content { 
      padding:32px; 
      color:#222; 
      font-size:1rem; 
      line-height:1.6; 
   }
   .footer { 
      background:#f2f2f2; 
      color:#888; 
      text-align:center; 
      font-size:0.9rem; 
      padding:16px 32px; 
   }
   .success-icon {
      font-size: 72px;
      color: #4CAF50;
      margin: 20px 0;
   }
   .btn {
      display: inline-block;
      background: #D81616;
      color: #fff !important;
      text-decoration: none;
      padding: 12px 28px;
      border-radius: 4px;
      margin-top: 24px;
      font-weight: bold;
      letter-spacing: 1px;
   }
</style>

<div class="container">
   <div class="header">
      <h1>Wahing Medical Clinic</h1>
   </div>
   
   <div class="content">
      <div class="success-icon">✓</div>
      <h2>Email Verified Successfully!</h2>
      
      <p>Thank you for verifying your email address. Your account is now fully activated.</p>
      
      <p>You can now access all features of your Wahing Medical Clinic account.</p>
      
      <p style="margin-top: 30px; font-size: 0.9em; color: #666;">
         Didn't request this verification? Please contact our support team immediately.
      </p>
   </div>
   
   <div class="footer">
      &copy; <?php echo date('Y'); ?> Wahing Medical Clinic. All rights reserved.
   </div>
</div>