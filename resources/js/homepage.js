// Typing effect
function typeWriter(element, text, speed, callback) {
    let i = 0;
    element.innerHTML = '';
    function type() {
        if (i < text.length) {
            if (text.charAt(i) === '<' && text.substr(i, 4) === '<br>') {
                element.innerHTML += '<br>';
                i += 4;
            } else {
                element.innerHTML += text.charAt(i);
                i++;
            }
            setTimeout(type, speed);
        } else {
            if (callback) callback();
        }
    }
    type();
}

window.addEventListener('load', () => {
    const subtitle = document.getElementById('subtitle');
    const subtitleText = "The Beginning of Her Story";
    
    setTimeout(() => {
        typeWriter(subtitle, subtitleText, 80);
    }, 1500);
    
    // Show toast if it exists
    const toast = document.getElementById('toast');
    if (toast) {
        setTimeout(() => {
            toast.classList.add('show');
        }, 500);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }
});

// Hamburger menu functionality
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');
const menuOverlay = document.getElementById('menuOverlay');
const closeMenu = document.getElementById('closeMenu');
const navLinks = document.querySelectorAll('.nav-link');

function openMenu() {
    navMenu.classList.add('active');
    menuOverlay.classList.add('active');
    hamburger.classList.add('hide');
    document.body.style.overflow = 'hidden';
}

function closeMenuFunc() {
    navMenu.classList.remove('active');
    menuOverlay.classList.remove('active');
    hamburger.classList.remove('hide');
    document.body.style.overflow = '';
}

hamburger.addEventListener('click', openMenu);
closeMenu.addEventListener('click', closeMenuFunc);
menuOverlay.addEventListener('click', closeMenuFunc);

// Navbar active link functionality
const currentSection = window.location.hash || '#section1';
navLinks.forEach(link => {
    if (link.getAttribute('href') === currentSection) {
        link.classList.add('active');
    }
});

// Close menu when clicking nav links
navLinks.forEach(link => {
    link.addEventListener('click', function() {
        navLinks.forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        closeMenuFunc();
    });
});

// Update active link on scroll
window.addEventListener('scroll', () => {
    let current = '';
    const sections = document.querySelectorAll('section');
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 100)) {
            current = section.getAttribute('id');
        }
    });

    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
            link.classList.add('active');
        }
    });
});

// Section overlay images visibility based on scroll - HANDLES BOTH DESKTOP AND MOBILE
function handleSectionImages() {
    const section2 = document.getElementById('section2');
    const section3 = document.getElementById('section3');
    const section4 = document.getElementById('section4');
    
    // Get all overlay images (both desktop and mobile)
    const section2Images = section2 ? section2.querySelectorAll('.section-overlay-image') : [];
    const section3Images = section3 ? section3.querySelectorAll('.section-overlay-image') : [];
    const section4Images = section4 ? section4.querySelectorAll('.section-overlay-image') : [];
    
    // Handle Section 2 Images (both desktop and mobile)
    if (section2 && section2Images.length > 0) {
        const section2Rect = section2.getBoundingClientRect();
        const isSection2Visible = section2Rect.top < window.innerHeight && section2Rect.bottom > 0;
        
        section2Images.forEach(img => {
            if (isSection2Visible) {
                img.classList.add('visible');
            } else {
                img.classList.remove('visible');
            }
        });
    }
    
    // Handle Section 3 Images (both desktop and mobile)
    if (section3 && section3Images.length > 0) {
        const section3Rect = section3.getBoundingClientRect();
        const isSection3Visible = section3Rect.top < window.innerHeight && section3Rect.bottom > 0;
        
        section3Images.forEach(img => {
            if (isSection3Visible) {
                img.classList.add('visible');
            } else {
                img.classList.remove('visible');
            }
        });
    }
    
    // Handle Section 4 Images (both desktop and mobile)
    if (section4 && section4Images.length > 0) {
        const section4Rect = section4.getBoundingClientRect();
        const isSection4Visible = section4Rect.top < window.innerHeight && section4Rect.bottom > 0;
        
        section4Images.forEach(img => {
            if (isSection4Visible) {
                img.classList.add('visible');
            } else {
                img.classList.remove('visible');
            }
        });
    }
}

// Run on scroll
window.addEventListener('scroll', handleSectionImages);
// Run on load
window.addEventListener('load', handleSectionImages);

// RSVP Form functionality
const attendingRadios = document.querySelectorAll('input[name="attending"]');
const guestsSection = document.getElementById('guestsSection');
const addGuestBtn = document.getElementById('addGuestBtn');
const additionalGuests = document.getElementById('additionalGuests');

// Show/hide guests section based on attendance
attendingRadios.forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'yes') {
            guestsSection.style.display = 'block';
        } else {
            guestsSection.style.display = 'none';
        }
    });
});

// Check on page load if attending is already selected
const selectedAttending = document.querySelector('input[name="attending"]:checked');
if (selectedAttending && selectedAttending.value === 'yes') {
    guestsSection.style.display = 'block';
}

// Add more guest inputs
if (addGuestBtn) {
    addGuestBtn.addEventListener('click', function() {
        const guestInputGroup = document.createElement('div');
        guestInputGroup.className = 'guest-input-group';
        
        const guestInput = document.createElement('input');
        guestInput.type = 'text';
        guestInput.name = 'additional_guests[]';
        guestInput.placeholder = 'Guest name';
        guestInput.className = 'guest-input';
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.innerHTML = '×';
        removeBtn.className = 'remove-guest-btn';
        removeBtn.style.cssText = `
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
        `;
        
        removeBtn.addEventListener('click', function() {
            guestInputGroup.remove();
        });
        
        removeBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        removeBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
        
        guestInputGroup.appendChild(guestInput);
        guestInputGroup.appendChild(removeBtn);
        
        additionalGuests.appendChild(guestInputGroup);
    });
}

// Form validation
const rsvpForm = document.getElementById('rsvpForm');
if (rsvpForm) {
    rsvpForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const attending = document.querySelector('input[name="attending"]:checked');
        
        if (!name || !email || !attending) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        // Show loading state on submit button
        const submitBtn = this.querySelector('.submit-btn');
        const btnText = submitBtn.querySelector('.btn-text');
        btnText.textContent = 'Sending...';
        submitBtn.disabled = true;
    });
}