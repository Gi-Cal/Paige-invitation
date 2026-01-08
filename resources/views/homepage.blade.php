<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Homepage</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    @vite(['resources/sass/app.scss', 'resources/js/homepage.js'])
</head>
    <style>
        #section1 {
            background-image: url('{{ asset('assets/images/section1.png') }}');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
        
        #section2 {
            background-image: url('{{ asset('assets/images/section2.png') }}');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
        
        #section3 {
            background-image: url('{{ asset('assets/images/section2.png') }}');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        #section4 {
            background-image: url('{{ asset('assets/images/section2.png') }}');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
    
<body>

    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- Toast Notification -->
    @if(session('success'))
        <div class="toast" id="toast">
            <span class="toast-icon">✨</span>
            <div class="toast-message">{{ session('success') }}</div>
        </div>
    @endif

    <nav>
        <div class="nav-container">
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            
            <ul id="navMenu">
                <span class="close-menu" id="closeMenu">&times;</span>
                <li><a href="#section1" class="nav-link">Home</a></li>
                <li><a href="#section2" class="nav-link">Event Details</a></li>
                <li><a href="#section3" class="nav-link">Reminders</a></li>
                <li><a href="#section4" class="nav-link">RSVP</a></li>
            </ul>
        </div>
    </nav>

    <section id="section1">
        <div class="content1">
            <h1 id="mainTitle">PAIGE ONE</h1>
            <hr>
            <h2 id="subtitle"></h2>
            <p class="subtext">
                A First Birthday Celebration<br>for<br><span class="highlight-name">Paige</span><br><br>Where wishes sparkle,<br>dreams take flight,<br>and a new adventure begins…
            </p>
        </div>
    </section>

    <section id="section2">
        <img src="{{ asset('assets/images/section2.1.png') }}" alt="Decoration" class="section-overlay-image desktop-overlay"> 
        <img src="{{ asset('assets/images/section2.1tv.png') }}" alt="Decoration" class="section-overlay-image tablet-overlay">
        <img src="{{ asset('assets/images/section2.1mv.png') }}" alt="Decoration" class="section-overlay-image mobile-overlay"> 

        <div class="container">
            <div class="top">
                <img src="{{ asset('assets/images/top.png') }}" alt="Top decoration"/>
            </div>
            <h1>THE GRAND CELEBRATION</h1>
            <h4>When the clock strikes two, the festivities shall begin</h4>
            <div class="date">
                <h2>DATE</h2>
                <p>Wednesday, January 21, 2026</p>
            </div>
            <div class="time">
                <h2>TIME</h2>
                <p>5:00 PM - 7:00 PM</p>
            </div>
            <div class="venue">
                <h2>VENUE</h2>
                <p>Jollibee Acacia Lane, Mandaluyong City</p>
            </div>
            <div class="down">
                <img src="{{ asset('assets/images/down.png') }}" alt="Down decoration"/>
            </div>
        </div>
    </section>

    <section id="section3">
        <img src="{{ asset('assets/images/section3.1.png') }}" alt="Decoration" class="section-overlay-image desktop-overlay">
        <img src="{{ asset('assets/images/section3.1tv.png') }}" alt="Decoration" class="section-overlay-image tablet-overlay">
        <img src="{{ asset('assets/images/section3.1mv.png') }}" alt="Decoration" class="section-overlay-image mobile-overlay">

        <div class="notes-container">
            <div class="notes-header">
                <h1>Important Notes</h1>
                <p>A few gentle reminders for our cherished guests</p>
            </div>

            <div class="notes-grid">
                <div class="note-card">
                    <div class="note-icon">🎭</div>
                    <h2>Dress Up Fun</h2>
                    <p>We'd be so happy to see everyone in costume, it's highly appreciated! If not, cartoon, Disney, or book-character outfits, shirts, or fun headbands & accessories will still make the day extra special.</p>
                </div>

                <div class="note-card">
                    <div class="note-icon">🎁</div>
                    <h2>Gifts Optional</h2>
                    <p>Your presence is the greatest gift. However, should you wish to bring something, we've prepared a small wish list below:</p>
                    <ul class="gift-list">
                        <li>EQ Diaper (Large)</li>
                        <li>Mustela Products (Bath/Lotion)</li>
                        <li>Monetary gift</li>
                    </ul>
                </div>

                <div class="note-card">
                    <div class="note-icon">📷</div>
                    <h2>Capture the Magic</h2>
                    <p>Feel free to take photos and share the memories!</p>
                </div>

                <div class="note-card">
                    <div class="note-icon">✈</div>
                    <h2>RSVP Soon</h2>
                    <p>Please let us know if you can join by December 15th, so we can prepare the feast!</p>
                </div>
            </div>
        </div>
    </section>

<section id="section4">
    <img src="{{ asset('assets/images/section4.1.png') }}" alt="Decoration" class="section-overlay-image desktop-overlay">
    <img src="{{ asset('assets/images/section4.1tv.png') }}" alt="Decoration" class="section-overlay-image tablet-overlay">
    <img src="{{ asset('assets/images/section4.1mv.png') }}" alt="Decoration" class="section-overlay-image mobile-overlay">
    
    <div class="rsvp-container">
        <div class="mirror-frame">
            <div class="mirror-content">
                <!-- Fixed Header (Doesn't Scroll) -->
                <div class="rsvp-header">
                    <h1 class="rsvp-title">RSVP</h1>
                    <p class="rsvp-subtitle">Join us in this magical celebration</p>
                </div>

                <!-- Scrollable Form -->
                <div class="rsvp-form-wrapper">
                    <form method="POST" action="{{ route('rsvp.store') }}" id="rsvpForm">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}">
                            @error('name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}">
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Will you attend? <span class="required">*</span></label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="attending" value="yes" required {{ old('attending') === 'yes' ? 'checked' : '' }}>
                                    <span>Yes, I'll be there!</span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="attending" value="no" {{ old('attending') === 'no' ? 'checked' : '' }}>
                                    <span>Unfortunately, I can't make it</span>
                                </label>
                            </div>
                            @error('attending')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="guestsSection" style="display: none;">
                            <label>Is there anyone else with you?</label>
                            <div id="additionalGuests">
                                <div class="guest-input-group">
                                    <input type="text" name="additional_guests[]" placeholder="Guest name" class="guest-input">
                                </div>
                            </div>
                            <button type="button" id="addGuestBtn" class="add-guest-btn">
                                <span>+</span> Add Another Guest
                            </button>
                        </div>

                        <div class="form-group">
                            <label for="message">Message (Optional)</label>
                            <textarea id="message" name="message" rows="4" placeholder="Share your wishes or any special notes...">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="submit-btn">
                            <span class="btn-text">Send RSVP</span>
                            <span class="btn-sparkle">✨</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
    
    <section id="footer">
        <center><p>Designed & Built by Gian Librada</p></center>
    </section>

</body>
</html>