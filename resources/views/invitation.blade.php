<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Invited</title>
    @vite(['resources/sass/app.scss', 'resources/js/invitation.js'])
</head>
<body>
    <div class="front-curtain"></div>
    
    <div class="stage">
        <div class="spotlight"></div>
        
        <div class="curtain-container">
            <div class="curtain curtain-left" id="curtainLeft"></div>
            <div class="curtain curtain-right" id="curtainRight"></div>
        </div>

        <div class="invitation" id="invitation">
            YOU ARE INVITED
        </div>

        <div class="rope-container">
            <div class="rope" id="rope">
                <div class="rope-strand"></div>
                <div class="rope-twist"></div>
                <div class="rope-end"></div>
                <div class="tassel-holder"></div>
                <div class="tassel"></div>
                <div class="tassel-strands">
                    <div class="tassel-strand"></div>
                    <div class="tassel-strand"></div>
                    <div class="tassel-strand"></div>
                    <div class="tassel-strand"></div>
                    <div class="tassel-strand"></div>
                    <div class="tassel-strand"></div>
                    <div class="tassel-strand"></div>
                </div>
            </div>
        </div>

        <div class="wooden-floor"></div>
    </div>
</body>
</html>