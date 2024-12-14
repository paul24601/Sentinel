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

//autofill
document.getElementById('autofillButton').addEventListener('click', function () {
    // Random number generator
    const randomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;
    const randomFloat = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

    // Autofill function
    const autofillField = (id, value) => {
        const field = document.getElementById(id);
        if (field) {
            field.value = value;
        }
    };

    // Product and Machine Information
    autofillField('date', new Date().toISOString().split('T')[0]);
    autofillField('time', new Date().toLocaleTimeString('en-US', { hour12: false }).slice(0, 5));
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
    autofillField('grossWeight', randomFloat(100, 200));
    autofillField('netWeight', randomFloat(50, 150));

    // Material Composition
    autofillField('dryingtime', randomFloat(1, 10));
    autofillField('dryingtemp', randomFloat(50, 100));
    for (let i = 1; i <= 4; i++) {
        autofillField(`type${i}`, `Type-${i}`);
        autofillField(`brand${i}`, `Brand-${i}`);
        autofillField(`mix${i}`, randomFloat(0, 100));
    }

    // Colorant Details
    autofillField('colorant', `Colorant-${randomInt(1, 5)}`);
    autofillField('colorant-dosage', `${randomFloat(0.5, 5)}%`);
    autofillField('colorant-stabilizer', `Stabilizer-${randomInt(1, 5)}`);
    autofillField('colorant-stabilizer-dosage', `${randomFloat(0.5, 2)}g`);

    // Timer Parameters
    autofillField('fillingTime', randomFloat(0.5, 5));
    autofillField('holdingTime', randomFloat(0.5, 5));
    autofillField('moldOpenCloseTime', randomFloat(0.5, 5));
    autofillField('chargingTime', randomFloat(0.5, 5));
    autofillField('coolingTime', randomFloat(0.5, 5));
    autofillField('cycleTime', randomFloat(0.5, 5));

    // Temperature Settings
    for (let i = 0; i <= 16; i++) {
        autofillField(`barrelHeaterZone${i}`, randomFloat(150, 300));
    }

    // Mold Open and Close Settings
    for (let i = 1; i <= 6; i++) {
        autofillField(`moldOpenPos${i}`, randomFloat(0, 500));
        autofillField(`moldOpenSpd${i}`, randomFloat(0, 100));
        autofillField(`moldOpenPressure${i}`, randomFloat(0, 200));

        autofillField(`moldClosePos${i}`, randomFloat(0, 500));
        autofillField(`moldCloseSpd${i}`, randomFloat(0, 100));
        autofillField(`moldClosePressure${i}`, randomFloat(0, 200));
    }

    // Plasticizing Parameters
    for (let i = 1; i <= 3; i++) {
        autofillField(`screwRPM${i}`, randomInt(10, 100));
        autofillField(`screwSpeed${i}`, randomInt(10, 100));
        autofillField(`plastPressure${i}`, randomFloat(50, 200));
        autofillField(`plastPosition${i}`, randomFloat(10, 100));
        autofillField(`backPressure${i}`, randomFloat(10, 50));
    }
    autofillField('backPressureStartPosition', randomFloat(10, 100));

    // Injection Parameters
    autofillField('recoveryPOS', randomFloat(10, 50));
    autofillField('secondStagePosition', randomFloat(10, 50));
    autofillField('cushion', randomFloat(1, 10));
    for (let i = 1; i <= 6; i++) {
        autofillField(`screwPosition${i}`, randomFloat(10, 100));
        autofillField(`injectionSpeed${i}`, randomFloat(10, 100));
        autofillField(`injectionPressure${i}`, randomFloat(50, 200));
    }

    // Ejection Parameters
    autofillField('airBlowTimeA', randomFloat(1, 10));
    autofillField('airBlowPositionA', randomFloat(10, 50));
    autofillField('airBlowADelay', randomFloat(1, 5));
    autofillField('airBlowTimeB', randomFloat(1, 10));
    autofillField('airBlowPositionB', randomFloat(10, 50));
    autofillField('airBlowBDelay', randomFloat(1, 5));

    // Core Pull Settings
    for (let i of ['A', 'B']) {
        autofillField(`coreSet${i}Sequence`, randomInt(1, 10));
        autofillField(`coreSet${i}Pressure`, randomFloat(10, 100));
        autofillField(`coreSet${i}Speed`, randomFloat(10, 100));
        autofillField(`coreSet${i}Position`, randomFloat(10, 100));
        autofillField(`coreSet${i}Time`, randomFloat(1, 10));
        autofillField(`coreSet${i}LimitSwitch`, `Switch-${randomInt(1, 5)}`);

        autofillField(`corePull${i}Sequence`, randomInt(1, 10));
        autofillField(`corePull${i}Pressure`, randomFloat(10, 100));
        autofillField(`corePull${i}Speed`, randomFloat(10, 100));
        autofillField(`corePull${i}Position`, randomFloat(10, 100));
        autofillField(`corePull${i}Time`, randomFloat(1, 10));
        autofillField(`corePull${i}LimitSwitch`, `Switch-${randomInt(1, 5)}`);
    }

    // Personnel
    autofillField('adjuster', `Adjuster-${randomInt(1, 100)}`);
    autofillField('qae', `QAE-${randomInt(1, 100)}`);
});
