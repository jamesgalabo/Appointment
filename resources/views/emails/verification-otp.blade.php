<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KidaBoard Verification Code</title>
  <style>
    body { margin: 0; padding: 0; background: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    .wrapper { max-width: 520px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); padding: 36px 40px 28px; text-align: center; }
    .logo { display: inline-flex; align-items: center; gap: 10px; }
    .logo-icon { width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; font-size: 18px; font-weight: 900; color: #fff; }
    .logo-text { font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
    .logo-text span { color: #c7d2fe; }
    .body { padding: 36px 40px; }
    .greeting { font-size: 16px; font-weight: 600; color: #1e293b; margin: 0 0 8px; }
    .sub { font-size: 14px; color: #64748b; line-height: 1.6; margin: 0 0 28px; }
    .otp-box { background: #f8fafc; border: 2px dashed #c7d2fe; border-radius: 16px; padding: 24px; text-align: center; margin: 0 0 28px; }
    .otp-label { font-size: 11px; font-weight: 700; color: #6366f1; letter-spacing: 1.5px; text-transform: uppercase; margin: 0 0 12px; }
    .otp-code { font-size: 42px; font-weight: 900; letter-spacing: 12px; color: #1e293b; font-variant-numeric: tabular-nums; }
    .otp-timer { font-size: 12px; color: #94a3b8; margin-top: 10px; }
    .note { background: #fef3c7; border-radius: 10px; padding: 14px 16px; font-size: 12px; color: #92400e; line-height: 1.5; }
    .note strong { color: #78350f; }
    .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 40px; text-align: center; font-size: 11px; color: #94a3b8; }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <div class="logo">
        <div class="logo-icon">KB</div>
        <div class="logo-text">Kida<span>Board</span></div>
      </div>
    </div>
    <div class="body">
      <p class="greeting">Hello, {{ $name }}!</p>
      <p class="sub">
        Thank you for registering on <strong>KidaBoard</strong> — the smart boarding house platform for Kidapawan City.
        Please use the verification code below to complete your registration.
      </p>
      <div class="otp-box">
        <div class="otp-label">Your Verification Code</div>
        <div class="otp-code">{{ $otp }}</div>
        <div class="otp-timer">This code expires in <strong>10 minutes</strong></div>
      </div>
      <div class="note">
        <strong>Security notice:</strong> Never share this code with anyone. KidaBoard staff will never ask for your verification code.
        If you did not create an account, please ignore this email.
      </div>
    </div>
    <div class="footer">
      &copy; {{ date('Y') }} KidaBoard &mdash; Smart Student Accommodation &bull; Kidapawan City
    </div>
  </div>
</body>
</html>
