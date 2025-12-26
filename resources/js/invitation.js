// Create curtain folds
const curtainLeft = document.getElementById('curtainLeft');
const curtainRight = document.getElementById('curtainRight');

for (let i = 0; i < 12; i++) {
    const fold = document.createElement('div');
    fold.className = 'fold';
    fold.style.left = (i * 8.33) + '%';
    curtainLeft.appendChild(fold);
}

for (let i = 0; i < 12; i++) {
    const fold = document.createElement('div');
    fold.className = 'fold';
    fold.style.left = (i * 8.33) + '%';
    curtainRight.appendChild(fold);
}

// Rope pull functionality
const rope = document.getElementById('rope');
const invitation = document.getElementById('invitation');

let isDragging = false;
let startY = 0;
let currentY = 0;
let ropeLength = 40;
const pullThreshold = 15;
let hasOpened = false;

rope.addEventListener('mousedown', startDrag);
rope.addEventListener('touchstart', startDrag);

document.addEventListener('mousemove', drag);
document.addEventListener('touchmove', drag);

document.addEventListener('mouseup', stopDrag);
document.addEventListener('touchend', stopDrag);

function startDrag(e) {
    if (hasOpened) return;
    isDragging = true;
    startY = e.type.includes('mouse') ? e.clientY : e.touches[0].clientY;
    rope.style.cursor = 'grabbing';
    e.preventDefault();
}

function drag(e) {
    if (!isDragging || hasOpened) return;
    
    currentY = e.type.includes('mouse') ? e.clientY : e.touches[0].clientY;
    const deltaY = currentY - startY;
    const percentagePull = (deltaY / window.innerHeight) * 100;
    
    ropeLength = Math.max(25, Math.min(65, 40 + percentagePull));
    rope.style.height = ropeLength + '%';
    
    const swingAngle = Math.min(8, Math.abs(percentagePull) * 0.4);
    rope.style.transform = `translateX(-50%) rotate(${percentagePull > 0 ? swingAngle : -swingAngle}deg)`;
    
    e.preventDefault();
}

function stopDrag(e) {
    if (!isDragging || hasOpened) return;
    isDragging = false;
    rope.style.cursor = 'grab';
    
    const deltaY = currentY - startY;
    const percentagePull = (deltaY / window.innerHeight) * 100;
    
    if (percentagePull > pullThreshold) {
        openCurtains();
    } else {
        rope.style.transition = 'height 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55), transform 0.6s ease';
        rope.style.height = '40%';
        rope.style.transform = 'translateX(-50%) rotate(0deg)';
        
        setTimeout(() => {
            rope.style.transition = 'height 0.1s ease';
        }, 600);
    }
}

function openCurtains() {
    hasOpened = true;
    
    invitation.classList.add('fade');
    
    curtainLeft.classList.add('open');
    curtainRight.classList.add('open');
    
    rope.style.transition = 'all 2.5s ease';
    rope.style.height = '15%';
    rope.style.transform = 'translateX(-50%) rotate(0deg)';
    
    setTimeout(() => {
        window.location.href = '/home';
    }, 2000);
}

// Add swaying animation after delay
setTimeout(() => {
    if (!hasOpened) {
        rope.style.animation = 'sway 4s ease-in-out infinite';
    }
}, 1000);