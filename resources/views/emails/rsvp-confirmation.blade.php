<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP Confirmation</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #f5e6d3 0%, #d4a574 100%);
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .header {
            background: linear-gradient(135deg, #D6C17A 0%, #b8a05f 100%);
            padding: 40px 20px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: '✨';
            position: absolute;
            top: 10px;
            left: 20px;
            font-size: 24px;
            animation: sparkle 2s infinite;
        }
        .header::after {
            content: '✨';
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 24px;
            animation: sparkle 2s infinite 1s;
        }
        @keyframes sparkle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .header h1 {
            color: #4d683e;
            font-size: 32px;
            margin: 0;
            font-family: 'Cinzel Decorative', serif;
            text-shadow: 2px 2px 4px rgba(255, 241, 170, 0.5);
        }
        .header p {
            color: #6a6a6a;
            font-style: italic;
            margin: 10px 0 0;
            font-size: 18px;
        }
        .content {
            padding: 40px 30px;
            color: #333;
        }
        .greeting {
            font-size: 20px;
            color: #4d683e;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .message {
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .event-details {
            background: linear-gradient(135deg, #fff9e6 0%, #fef2d9 100%);
            border-left: 5px solid #D6C17A;
            padding: 25px;
            margin: 30px 0;
            border-radius: 10px;
        }
        .event-details h2 {
            color: #4d683e;
            font-size: 24px;
            margin: 0 0 20px;
            font-family: 'Cinzel Decorative', serif;
        }
        .detail-item {
            margin: 15px 0;
            display: flex;
            align-items: flex-start;
        }
        .detail-label {
            color: #4d683e;
            font-weight: bold;
            min-width: 80px;
            font-size: 15px;
        }
        .detail-value {
            color: #666;
            font-size: 15px;
        }
        .reminders {
            margin-top: 30px;
        }
        .reminders h3 {
            color: #4d683e;
            font-size: 20px;
            margin-bottom: 15px;
            font-family: 'Cinzel Decorative', serif;
        }
        .reminder-item {
            background: #f9f9f9;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 3px solid #D6C17A;
        }
        .reminder-item strong {
            color: #4d683e;
            display: block;
            margin-bottom: 5px;
        }
        .contact-info {
            background: #fff9e6;
            border: 2px solid #D6C17A;
            padding: 20px;
            margin: 30px 0;
            border-radius: 10px;
            text-align: center;
        }
        .contact-info strong {
            color: #4d683e;
            font-size: 18px;
            display: block;
            margin-bottom: 10px;
        }
        .footer {
            background: #4d683e;
            color: #fff1aa;
            padding: 25px;
            text-align: center;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .magic-border {
            height: 3px;
            background: linear-gradient(90deg, transparent, #D6C17A, transparent);
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Chapter One</h1>
            <p>Paige's First Birthday Celebration</p>
        </div>

        <div class="content">
            <div class="greeting">Dear {{ $rsvpData['name'] }},</div>

            <div class="message">
                @if($rsvpData['attending'] === 'yes')
                    <p>✨ Thank you for confirming your attendance! We are absolutely delighted that you will be joining us for this magical celebration.</p>
                    
                    @if(isset($rsvpData['additional_guests']) && count(array_filter($rsvpData['additional_guests'])) > 0)
                        <p>We've noted that you'll be bringing: <strong>{{ implode(', ', array_filter($rsvpData['additional_guests'])) }}</strong></p>
                    @endif
                @else
                    <p>Thank you for letting us know. We're sorry you can't make it, but we appreciate you taking the time to respond.</p>
                    <p>If you change your mind or your plans change, please feel free to contact us at <strong>iamgiancarlli@gmail.com</strong>. We would love to have you join us!</p>
                @endif
            </div>

            <div class="magic-border"></div>

            <div class="event-details">
                <h2>📜 Event Details</h2>
                
                <div class="detail-item">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">Saturday, December 14th, 2024</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">2:00 PM - 5:00 PM</span>
                </div>
                
                <div class="detail-item">
                    <span class="detail-label">Venue:</span>
                    <span class="detail-value">The Enchanted Garden<br>123 Storybook Lane, Wonderland</span>
                </div>
            </div>

            @if($rsvpData['attending'] === 'yes')
            <div class="reminders">
                <h3>✨ Gentle Reminders</h3>
                
                <div class="reminder-item">
                    <strong>♡ Bring Your Joy</strong>
                    Your presence is the greatest gift of all. Come with a heart full of wonder!
                </div>
                
                <div class="reminder-item">
                    <strong>🎁 Gifts Optional</strong>
                    While not required, books for our little one's library would be treasured.
                </div>
                
                <div class="reminder-item">
                    <strong>📷 Capture the Magic</strong>
                    Feel free to take photos and share the memories. Use #FirstChapter on social media!
                </div>
            </div>
            @endif

            @if(isset($rsvpData['message']) && $rsvpData['message'])
            <div class="magic-border"></div>
            <div class="message">
                <p><strong>Your Message:</strong></p>
                <p style="font-style: italic; color: #666;">{{ $rsvpData['message'] }}</p>
            </div>
            @endif
            
            <div class="contact-info">
                <strong>Need to reach us?</strong>
                <p>Email: iamgiancarlli@gmail.com</p>
                <p>We're here to answer any questions!</p>
            </div>
        </div>

        <div class="footer">
            <p>✨ Where wishes sparkle, dreams take flight ✨</p>
            <p>and a new adventure begins...</p>
        </div>
    </div>
</body>
</html>