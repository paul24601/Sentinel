document.getElementById('uploadImage').addEventListener('change', function (event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

document.getElementById('uploadVideo').addEventListener('change', function (event) {
    const videoSource = document.getElementById('videoSource');
    const videoPreview = document.getElementById('videoPreview');
    const file = event.target.files[0];

    if (file) {
        const url = URL.createObjectURL(file);
        videoSource.src = url;
        videoPreview.load();
        videoPreview.style.display = 'block';
    }
});

// Autofill
document.getElementById('autofillButton').addEventListener('click', function () {
    // Random number generator for integers
    const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

    // Autofill function
    const autofillField = (id, value) => {
        const field = document.getElementById(id);
        if (field) {
            field.value = value;
        }
    };

    // Get Philippine Time
    const getPhilippineTime = () => {
        const now = new Date();
        return new Date(now.toLocaleString("en-US", {timeZone: "Asia/Manila"}));
    };

    // Product and Machine Information
    const phTime = getPhilippineTime();
    autofillField('date', phTime.toISOString().split('T')[0]);
    autofillField('time', phTime.toLocaleTimeString('en-US', { hour12: false, timeZone: 'Asia/Manila' }).slice(0, 5));
    autofillField('machine', `Machine-${randomInt(1, 10)}`);
    autofillField('runNo', `Run-${randomInt(1000, 9999)}`);
    autofillField('category', `Category-${randomInt(1, 5)}`);
    autofillField('IRN', `IRN-${randomInt(100000, 999999)}`);

    // Product Details
    autofillField('product', `Product-${randomInt(1, 100)}`);
    autofillField('color', `Color-${randomInt(1, 10)}`);
    autofillField('mold-name', `Mold-${randomInt(1, 50)}`);
    autofillField('prodNo', `Prod-${randomInt(1000, 9999)}`);
    autofillField('cavity', randomInt(1, 8));
    autofillField('grossWeight', randomInt(100, 200));
    autofillField('netWeight', randomInt(50, 150));

    // Material Composition
    autofillField('dryingtime', randomInt(1, 10));
    autofillField('dryingtemp', randomInt(50, 100));
    for (let i = 1; i <= 4; i++) {
        autofillField(`type${i}`, `Type-${i}`);
        autofillField(`brand${i}`, `Brand-${i}`);
        autofillField(`mix${i}`, randomInt(0, 100));
    }

    // Colorant Details
    autofillField('colorant', `Colorant-${randomInt(1, 5)}`);
    autofillField('colorant-dosage', `${randomInt(1, 5)}%`);
    autofillField('colorant-stabilizer', `Stabilizer-${randomInt(1, 5)}`);
    autofillField('colorant-stabilizer-dosage', `${randomInt(1, 2)}g`);

    // Timer Parameters
    autofillField('fillingTime', randomInt(1, 5));
    autofillField('holdingTime', randomInt(1, 5));
    autofillField('moldOpenCloseTime', randomInt(1, 5));
    autofillField('chargingTime', randomInt(1, 5));
    autofillField('coolingTime', randomInt(1, 5));
    autofillField('cycleTime', randomInt(1, 5));

    // Temperature Settings
    for (let i = 0; i <= 16; i++) {
        autofillField(`barrelHeaterZone${i}`, randomInt(150, 300));
    }

    // Mold Open and Close Settings
    for (let i = 1; i <= 6; i++) {
        autofillField(`moldOpenPos${i}`, randomInt(0, 500));
        autofillField(`moldOpenSpd${i}`, randomInt(0, 100));
        autofillField(`moldOpenPressure${i}`, randomInt(0, 200));

        autofillField(`moldClosePos${i}`, randomInt(0, 500));
        autofillField(`moldCloseSpd${i}`, randomInt(0, 100));
        autofillField(`moldClosePressure${i}`, randomInt(0, 200));
    }

    // Plasticizing Parameters
    for (let i = 1; i <= 3; i++) {
        autofillField(`screwRPM${i}`, randomInt(10, 100));
        autofillField(`screwSpeed${i}`, randomInt(10, 100));
        autofillField(`plastPressure${i}`, randomInt(50, 200));
        autofillField(`plastPosition${i}`, randomInt(10, 100));
        autofillField(`backPressure${i}`, randomInt(10, 50));
    }
    autofillField('backPressureStartPosition', randomInt(10, 100));

    // Injection Parameters
    autofillField('recoveryPOS', randomInt(10, 50));
    autofillField('secondStagePosition', randomInt(10, 50));
    autofillField('cushion', randomInt(1, 10));
    for (let i = 1; i <= 6; i++) {
        autofillField(`screwPosition${i}`, randomInt(10, 100));
        autofillField(`injectionSpeed${i}`, randomInt(10, 100));
        autofillField(`injectionPressure${i}`, randomInt(50, 200));
    }

    // Ejection Parameters
    autofillField('airBlowTimeA', randomInt(1, 10));
    autofillField('airBlowPositionA', randomInt(10, 50));
    autofillField('airBlowADelay', randomInt(1, 5));
    autofillField('airBlowTimeB', randomInt(1, 10));
    autofillField('airBlowPositionB', randomInt(10, 50));
    autofillField('airBlowBDelay', randomInt(1, 5));

    // Core Pull Settings
    for (let i of ['A', 'B']) {
        autofillField(`coreSet${i}Sequence`, randomInt(1, 10));
        autofillField(`coreSet${i}Pressure`, randomInt(10, 100));
        autofillField(`coreSet${i}Speed`, randomInt(10, 100));
        autofillField(`coreSet${i}Position`, randomInt(10, 100));
        autofillField(`coreSet${i}Time`, randomInt(1, 10));
        autofillField(`coreSet${i}LimitSwitch`, `Switch-${randomInt(1, 5)}`);

        autofillField(`corePull${i}Sequence`, randomInt(1, 10));
        autofillField(`corePull${i}Pressure`, randomInt(10, 100));
        autofillField(`corePull${i}Speed`, randomInt(10, 100));
        autofillField(`corePull${i}Position`, randomInt(10, 100));
        autofillField(`corePull${i}Time`, randomInt(1, 10));
        autofillField(`corePull${i}LimitSwitch`, `Switch-${randomInt(1, 5)}`);
    }

    // Personnel
    autofillField('adjuster', `Adjuster-${randomInt(1, 100)}`);
    autofillField('qae', `QAE-${randomInt(1, 100)}`);
});

// Session timeout handling
let sessionTimeout;
const TIMEOUT_DURATION = 30 * 60 * 1000; // 30 minutes in milliseconds
const WARNING_DURATION = 5 * 60 * 1000; // 5 minutes in milliseconds

function resetSessionTimer() {
    clearTimeout(sessionTimeout);
    sessionTimeout = setTimeout(showTimeoutWarning, TIMEOUT_DURATION - WARNING_DURATION);
}

function showTimeoutWarning() {
    // Create warning modal
    const warningModal = `
        <div class="modal fade" id="sessionTimeoutModal" tabindex="-1" role="dialog" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sessionTimeoutModalLabel">Session Timeout Warning</h5>
                    </div>
                    <div class="modal-body">
                        <p>Your session will expire in 5 minutes due to inactivity.</p>
                        <p>Would you like to continue your session?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="logout()">Logout</button>
                        <button type="button" class="btn btn-primary" onclick="extendSession()">Continue Session</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Add modal to body if it doesn't exist
    if (!document.getElementById('sessionTimeoutModal')) {
        document.body.insertAdjacentHTML('beforeend', warningModal);
    }

    // Show the modal
    $('#sessionTimeoutModal').modal('show');

    // Set timeout for actual session expiration
    setTimeout(logout, WARNING_DURATION);
}

function extendSession() {
    // Make an AJAX call to refresh the session
    $.ajax({
        url: 'refresh_session.php',
        method: 'POST',
        success: function(response) {
            // Hide the warning modal
            $('#sessionTimeoutModal').modal('hide');
            // Reset the session timer
            resetSessionTimer();
        },
        error: function() {
            // If the refresh fails, log out
            logout();
        }
    });
}

function logout() {
    window.location.href = '../login.html';
}

// Reset timer on user activity
document.addEventListener('mousemove', resetSessionTimer);
document.addEventListener('keypress', resetSessionTimer);
document.addEventListener('click', resetSessionTimer);
document.addEventListener('scroll', resetSessionTimer);

// Initialize the session timer when the page loads
document.addEventListener('DOMContentLoaded', resetSessionTimer);
