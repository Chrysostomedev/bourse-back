<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background-color: #f8f7fc; }
        .container { max-width: 600px; margin: 0 auto; background-color: #f8f7fc; }
        .header { text-align: center; padding: 32px 0 16px 0; background-color: #f8f7fc; }
        .logo-container { margin-bottom: 24px; }
        .logo-text { font-family: Inter, sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 1.6px; text-transform: uppercase; color: #6B2D90; margin-top: 12px; }
        .logo-text .pink { color: #F25C5C; }
        .card { max-width: 440px; background: #ffffff; border-radius: 16px; border: 1px solid #ede9f3; margin: 0 auto 32px; overflow: hidden; box-shadow: 0 4px 24px rgba(107,45,144,0.06); }
        .accent-bar { height: 4px; background: linear-gradient(90deg, #6B2D90 0%, #8e4dbd 60%, #F25C5C 100%); }
        .card-content { padding: 36px; }
        .card-title { margin: 0 0 8px 0; font-size: 22px; font-weight: 700; color: #1a1625; letter-spacing: -0.3px; }
        .card-subtitle { margin: 0 0 20px 0; font-size: 14.5px; line-height: 22px; color: #5a5671; }
        .otp-box { display: inline-block; background: #faf8ff; border: 1px dashed #dccdf0; border-radius: 12px; padding: 18px 28px; text-align: center; }
        .otp-code { font-family: 'SF Mono', ui-monospace, Menlo, monospace; font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #6B2D90; line-height: 1; }
        .otp-expires { display: inline-block; font-family: Inter, sans-serif; font-size: 12px; font-weight: 600; color: #6B2D90; background: #f1e9f9; border-radius: 100px; padding: 6px 12px; margin-top: 14px; }
        .card-footer { padding: 8px 36px 32px 36px; text-align: center; font-family: Inter, sans-serif; font-size: 13px; line-height: 20px; color: #8b87a3; margin: 0; }
        .footer-section { text-align: center; padding: 0 0 32px 0; font-family: Inter, sans-serif; font-size: 12px; color: #9a96b0; }
        .footer-divider { margin-top: 8px; width: 24px; height: 2px; background: #F25C5C; margin-left: auto; margin-right: auto; border-radius: 2px; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header Logo --}}
        <div class="header">
            <div class="logo-container">
                <div class="logo-text">
                    BOURSE <span class="pink">POUR TOUS</span>
                </div>
            </div>
        </div>

        {{-- Card principale --}}
        <div class="card">
            <div class="accent-bar"></div>
            <div class="card-content">
                <h1 class="card-title">Votre code de vérification</h1>
                <p class="card-subtitle">
                    Voici votre code à usage unique pour continuer sur <strong>{{ config('app.name') }}</strong>.
                </p>
                
                <div style="text-align: center; margin: 20px 0;">
                    <div class="otp-box">
                        <div class="otp-code">{{ $code }}</div>
                    </div>
                    <div class="otp-expires">⏱ Expire dans 10 minutes</div>
                </div>

                <p class="card-footer">
                    Si vous n'êtes pas à l'origine de cette demande, ignorez simplement cet email.
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer-section">
            Merci,<br>
            <strong style="color: #1a1625; font-weight: 600;">L'équipe {{ config('app.name') }}</strong>
            <div class="footer-divider"></div>
        </div>
    </div>
</body>
</html>