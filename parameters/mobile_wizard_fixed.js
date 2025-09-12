class MobileWizard {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 12;
        this.formData = window.wizardData || {};
        this.autoSaveInterval = null;
        this.saveTimeout = null;
        this.isSubmitting = false;
        
        this.init();
    }
    
    init() {
        this.loadStep(1);
        this.setupEventListeners();
        this.startAutoSave();
        this.loadSavedProgress();
    }
    
    setupEventListeners() {
        $('#next-btn').on('click', () => this.nextStep());
        $('#prev-btn').on('click', () => this.prevStep());
        $('#submit-btn').on('click', () => this.submitForm());
        
        // Auto-save on input changes
        $(document).on('input change', '.form-control, .form-select', (e) => {
            this.saveFieldData(e.target);
            this.validateField(e.target);
        });
        
        // Prevent form submission on Enter key
        $(document).on('keypress', 'form', (e) => {
            if (e.which === 13) {
                e.preventDefault();
                this.nextStep();
            }
        });
    }
    
    loadStep(stepNumber) {
        const stepData = this.getStepData(stepNumber);
        const html = this.generateStepHTML(stepData);
        
        $('#wizard-content').fadeOut(200, () => {
            $('#wizard-content').html(html).fadeIn(200);
            this.populateFieldsFromData();
            this.updateProgress();
            this.updateNavigationButtons();
        });
    }
    
    getStepData(stepNumber) {
        const steps = {
            1: {
                title: 'Product & Machine Information',
                description: 'Basic product and machine details',
                fields: [
                    { name: 'MachineName', label: 'Machine Name', type: 'select', 
                      options: ['CLF 750B', 'CLF 880B', 'CLF 1100B', 'CLF 1300B', 'CLF 1600B'], required: true },
                    { name: 'RunNumber', label: 'Run Number', type: 'text', required: true },
                    { name: 'Category', label: 'Category', type: 'select', 
                      options: ['Colorant Testing', 'Customer Approval', 'Formal Production', 'Material Testing', 'Mold Testing', 'Parameter Development', 'Process Optimization'], required: true },
                    { name: 'IRN', label: 'IRN', type: 'text', required: true },
                    // Hidden fields for timestamps
                    { name: 'Date', label: '', type: 'hidden', value: new Date().toISOString().split('T')[0] },
                    { name: 'Time', label: '', type: 'hidden', value: new Date().toTimeString().split(' ')[0] },
                    { name: 'startTime', label: '', type: 'hidden', value: new Date().toTimeString().split(' ')[0] },
                    { name: 'endTime', label: '', type: 'hidden', value: '' },
                    { name: 'formStartTimestamp', label: '', type: 'hidden', value: Date.now().toString() }
                ]
            },
            2: {
                title: 'Product Details',
                description: 'Detailed product specifications',
                fields: [
                    { name: 'product', label: 'Product Name', type: 'text', required: true },
                    { name: 'color', label: 'Color', type: 'text', required: true },
                    { name: 'prodNo', label: 'Product Number', type: 'text', required: true },
                    { name: 'mold-name', label: 'Mold Name', type: 'text', required: true },
                    { name: 'cavity', label: 'Number of Cavity (Active)', type: 'number', required: true },
                    { name: 'grossWeight', label: 'Gross Weight', type: 'text', required: true },
                    { name: 'netWeight', label: 'Net Weight', type: 'text', required: true }
                ]
            },
            3: {
                title: 'Material Composition',
                description: 'Material specifications - at least Material 1 is required',
                fields: [
                    { name: 'dryingtime', label: 'Drying Time (hours)', type: 'number' },
                    { name: 'dryingtemp', label: 'Drying Temperature (°C)', type: 'number' },
                    { name: 'type1', label: 'Material Type 1', type: 'text', required: true },
                    { name: 'brand1', label: 'Brand 1', type: 'text', required: true },
                    { name: 'mix1', label: 'Mix 1 (%)', type: 'number', required: true },
                    { name: 'type2', label: 'Material Type 2', type: 'text' },
                    { name: 'brand2', label: 'Brand 2', type: 'text' },
                    { name: 'mix2', label: 'Mix 2 (%)', type: 'number' },
                    { name: 'type3', label: 'Material Type 3', type: 'text' },
                    { name: 'brand3', label: 'Brand 3', type: 'text' },
                    { name: 'mix3', label: 'Mix 3 (%)', type: 'number' },
                    { name: 'type4', label: 'Material Type 4', type: 'text' },
                    { name: 'brand4', label: 'Brand 4', type: 'text' },
                    { name: 'mix4', label: 'Mix 4 (%)', type: 'number' }
                ]
            },
            4: {
                title: 'Colorant Details',
                description: 'Color specifications (Optional)',
                optional: true,
                fields: [
                    { name: 'colorant', label: 'Colorant', type: 'text' },
                    { name: 'colorantColor', label: 'Colorant Color', type: 'text' },
                    { name: 'colorant-dosage', label: 'Colorant Dosage', type: 'text' },
                    { name: 'colorant-stabilizer', label: 'Colorant Stabilizer', type: 'text' },
                    { name: 'colorant-stabilizer-dosage', label: 'Stabilizer Dosage (grams)', type: 'number' }
                ]
            },
            5: {
                title: 'Mold & Operation Specifications',
                description: 'Mold and operation specifications',
                fields: [
                    { name: 'mold-code', label: 'Mold Code', type: 'text', required: true },
                    { name: 'clamping-force', label: 'Clamping Force', type: 'text', required: true },
                    { name: 'operation-type', label: 'Operation Type', type: 'select', 
                      options: ['Auto', 'Semi-Auto', 'Manual'], required: true },
                    { name: 'stationary-cooling-media', label: 'Stationary Cooling Media', type: 'select',
                      options: ['Water', 'Oil', 'Air', 'None'] },
                    { name: 'movable-cooling-media', label: 'Movable Cooling Media', type: 'select',
                      options: ['Water', 'Oil', 'Air', 'None'] },
                    { name: 'heating-media-type', label: 'Heating Media Type', type: 'select',
                      options: ['Electric', 'Oil', 'Steam', 'None'] },
                    { name: 'cooling-media-remarks', label: 'Cooling Media Remarks', type: 'textarea' }
                ]
            },
            6: {
                title: 'Timer Parameters',
                description: 'Cycle timing parameters',
                fields: [
                    { name: 'fillingTime', label: 'Filling Time (seconds)', type: 'number', required: true },
                    { name: 'holdingTime', label: 'Holding Time (seconds)', type: 'number', required: true },
                    { name: 'moldOpenCloseTime', label: 'Mold Open/Close Time (seconds)', type: 'number' },
                    { name: 'chargingTime', label: 'Charging Time (seconds)', type: 'number' },
                    { name: 'coolingTime', label: 'Cooling Time (seconds)', type: 'number', required: true },
                    { name: 'cycleTime', label: 'Cycle Time (seconds)', type: 'number', required: true }
                ]
            },
            7: {
                title: 'Barrel Heater Temperatures',
                description: 'Temperature settings for barrel heaters',
                fields: [
                    { name: 'barrelHeaterZone0', label: 'Nozzle Temperature (°C)', type: 'number', required: true },
                    { name: 'barrelHeaterZone1', label: 'Barrel Heater Zone 1 (°C)', type: 'number', required: true },
                    { name: 'barrelHeaterZone2', label: 'Barrel Heater Zone 2 (°C)', type: 'number', required: true },
                    { name: 'barrelHeaterZone3', label: 'Barrel Heater Zone 3 (°C)', type: 'number' },
                    { name: 'barrelHeaterZone4', label: 'Barrel Heater Zone 4 (°C)', type: 'number' },
                    { name: 'barrelHeaterZone5', label: 'Barrel Heater Zone 5 (°C)', type: 'number' }
                ]
            },
            8: {
                title: 'Mold Heater Temperatures', 
                description: 'Temperature settings for mold heaters',
                fields: [
                    { name: 'moldHeaterZone1', label: 'Mold Heater Zone 1 (°C)', type: 'number' },
                    { name: 'moldHeaterZone2', label: 'Mold Heater Zone 2 (°C)', type: 'number' },
                    { name: 'moldHeaterZone3', label: 'Mold Heater Zone 3 (°C)', type: 'number' },
                    { name: 'moldHeaterZone4', label: 'Mold Heater Zone 4 (°C)', type: 'number' },
                    { name: 'moldHeaterZone5', label: 'Mold Heater Zone 5 (°C)', type: 'number' },
                    { name: 'moldHeaterZone6', label: 'Mold Heater Zone 6 (°C)', type: 'number' }
                ]
            },
            9: {
                title: 'Injection Parameters',
                description: 'Injection molding parameters',
                fields: [
                    { name: 'injectionPressure1', label: 'Injection Pressure 1 (bar)', type: 'number', required: true },
                    { name: 'injectionVelocity1', label: 'Injection Velocity 1 (mm/s)', type: 'number', required: true },
                    { name: 'injectionPosition1', label: 'Injection Position 1 (mm)', type: 'number' },
                    { name: 'injectionPressure2', label: 'Injection Pressure 2 (bar)', type: 'number' },
                    { name: 'injectionVelocity2', label: 'Injection Velocity 2 (mm/s)', type: 'number' },
                    { name: 'injectionPosition2', label: 'Injection Position 2 (mm)', type: 'number' },
                    { name: 'holdingPressure1', label: 'Holding Pressure 1 (bar)', type: 'number', required: true },
                    { name: 'holdingTime1', label: 'Holding Time 1 (seconds)', type: 'number' },
                    { name: 'holdingPressure2', label: 'Holding Pressure 2 (bar)', type: 'number' },
                    { name: 'holdingTime2', label: 'Holding Time 2 (seconds)', type: 'number' }
                ]
            },
            10: {
                title: 'Plasticizing Parameters',
                description: 'Plasticizing settings',
                fields: [
                    { name: 'backPressure', label: 'Back Pressure (bar)', type: 'number', required: true },
                    { name: 'screwRPM', label: 'Screw RPM', type: 'number', required: true },
                    { name: 'chargeStrokeLimit', label: 'Charge Stroke Limit (mm)', type: 'number' },
                    { name: 'decompressionStroke', label: 'Decompression Stroke (mm)', type: 'number' },
                    { name: 'cushionPosition', label: 'Cushion Position (mm)', type: 'number' }
                ]
            },
            11: {
                title: 'Ejection & Mold Parameters',
                description: 'Ejection and mold operation settings',
                fields: [
                    { name: 'ejectorForwardTime', label: 'Ejector Forward Time (seconds)', type: 'number' },
                    { name: 'ejectorSpeed', label: 'Ejector Speed (mm/s)', type: 'number' },
                    { name: 'ejectorPressure', label: 'Ejector Pressure (bar)', type: 'number' },
                    { name: 'moldCloseSpeed', label: 'Mold Close Speed (mm/s)', type: 'number', required: true },
                    { name: 'moldOpenSpeed', label: 'Mold Open Speed (mm/s)', type: 'number', required: true },
                    { name: 'moldCloseForce', label: 'Mold Close Force (kN)', type: 'number' },
                    { name: 'moldOpenForce', label: 'Mold Open Force (kN)', type: 'number' }
                ]
            },
            12: {
                title: 'Personnel & Additional Information',
                description: 'Personnel information and final notes',
                fields: [
                    { name: 'adjusterName', label: 'Adjuster Name', type: 'text', required: true },
                    { name: 'qae', label: 'QAE Name', type: 'select',
                      options: ['Adrian Gonzales', 'Angelica Agustin', 'Christine Lacsa', 'Cristina Ramos', 'Doris Villanueva', 'Gladys Repollo', 'Heriel Pelonia', 'Janrie Paredes', 'Jerome Alfano', 'John Kevin Benosa', 'Karla Ocumen', 'Keith Marquez', 'Ma. Teresa Gomez', 'Mariz Joy Macaspac', 'Mary Jean Gutierrez', 'Maverick Garcia', 'Noriel Fuellas', 'Rafael Manguiat', 'Richard Dizon', 'Romeo Agustin Jr.', 'Teresita Pena'], required: true },
                    { name: 'additionalInfo', label: 'Additional Information / Remarks', type: 'textarea' }
                ]
            }
        };
        
        return steps[stepNumber] || {};
    }
    
    generateStepHTML(stepData) {
        let html = `
            <div class="step-card">
                <div class="step-header">
                    <h3>${stepData.title}</h3>
                    <p>${stepData.description}</p>
                    ${stepData.optional ? '<span class="badge bg-secondary">Optional</span>' : ''}
                </div>
                <div class="step-content">
        `;
        
        if (stepData.fields && Array.isArray(stepData.fields)) {
            stepData.fields.forEach(field => {
                if (field.type === 'hidden') {
                    html += `<input type="hidden" name="${field.name}" value="${field.value || ''}" />`;
                    return;
                }
                
                html += `
                    <div class="form-group mb-3">
                        <label class="form-label">${field.label} ${field.required ? '<span class="text-danger">*</span>' : ''}</label>
                `;
                
                switch (field.type) {
                    case 'select':
                        html += `<select class="form-control form-select" name="${field.name}" ${field.required ? 'required' : ''}>`;
                        html += '<option value="">Select an option</option>';
                        if (field.options) {
                            field.options.forEach(option => {
                                html += `<option value="${option}">${option}</option>`;
                            });
                        }
                        html += '</select>';
                        break;
                    case 'textarea':
                        html += `<textarea class="form-control" name="${field.name}" rows="3" ${field.required ? 'required' : ''}></textarea>`;
                        break;
                    case 'range':
                        html += `
                            <div class="range-container">
                                <input type="range" class="form-range range-slider" name="${field.name}" 
                                       min="${field.min || 0}" max="${field.max || 100}" 
                                       oninput="updateRangeValue('${field.name}')" ${field.required ? 'required' : ''} />
                                <div class="range-value" id="${field.name}-value">0</div>
                            </div>
                        `;
                        break;
                    default:
                        html += `<input type="${field.type}" class="form-control" name="${field.name}" 
                                 ${field.required ? 'required' : ''} ${field.min ? 'min="' + field.min + '"' : ''} 
                                 ${field.max ? 'max="' + field.max + '"' : ''} ${field.step ? 'step="' + field.step + '"' : ''} />`;
                }
                
                html += '</div>';
            });
        }
        
        html += `
                </div>
            </div>
        `;
        
        return html;
    }
    
    saveFieldData(field) {
        const $field = $(field);
        const fieldName = $field.attr('name');
        
        if (fieldName) {
            if ($field.attr('type') === 'checkbox') {
                this.formData[fieldName] = $field.is(':checked');
            } else if ($field.attr('type') === 'radio') {
                if ($field.is(':checked')) {
                    this.formData[fieldName] = $field.val();
                }
            } else {
                this.formData[fieldName] = $field.val();
            }
            
            // Save to localStorage immediately
            this.saveToLocalStorage();
            
            // Debounced server save to avoid too many requests
            clearTimeout(this.saveTimeout);
            this.saveTimeout = setTimeout(() => {
                this.saveToServer();
            }, 2000); // Save to server 2 seconds after last change
        }
    }
    
    populateFieldsFromData() {
        Object.keys(this.formData).forEach(key => {
            const $field = $(`[name="${key}"]`);
            if ($field.length && this.formData[key]) {
                $field.val(this.formData[key]);
                
                // Update range value display
                if ($field.hasClass('range-slider')) {
                    updateRangeValue(key);
                }
            }
        });
    }
    
    validateField(field) {
        const $field = $(field);
        const $formGroup = $field.closest('.form-group');
        
        if ($field.prop('required') && !$field.val()) {
            $formGroup.addClass('has-error');
            $field.addClass('is-invalid');
        } else {
            $formGroup.removeClass('has-error');
            $field.removeClass('is-invalid').addClass('is-valid');
        }
    }
    
    nextStep() {
        if (this.validateCurrentStep()) {
            this.collectCurrentStepData();
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
                this.loadStep(this.currentStep);
            }
        }
    }
    
    prevStep() {
        if (this.currentStep > 1) {
            this.collectCurrentStepData();
            this.currentStep--;
            this.loadStep(this.currentStep);
        }
    }
    
    validateCurrentStep() {
        let isValid = true;
        $('.form-control[required], .form-select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                $(this).closest('.form-group').addClass('has-error');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $(this).closest('.form-group').removeClass('has-error');
            }
        });
        
        if (!isValid) {
            this.showNotification('Please fill in all required fields before proceeding.', 'error');
        }
        
        return isValid;
    }
    
    updateProgress() {
        const progress = (this.currentStep / this.totalSteps) * 100;
        $('#progress-bar').css('width', progress + '%');
        $('#progress-text').text(`Step ${this.currentStep} of ${this.totalSteps}`);
    }
    
    updateNavigationButtons() {
        $('#prev-btn').prop('disabled', this.currentStep === 1);
        
        if (this.currentStep === this.totalSteps) {
            $('#next-btn').addClass('d-none');
            $('#submit-btn').removeClass('d-none');
        } else {
            $('#next-btn').removeClass('d-none');
            $('#submit-btn').addClass('d-none');
        }
    }
    
    startAutoSave() {
        this.autoSaveInterval = setInterval(() => {
            this.saveToServer();
        }, 30000); // Auto-save every 30 seconds
    }
    
    saveToLocalStorage() {
        localStorage.setItem('wizard_progress', JSON.stringify({
            step: this.currentStep,
            data: this.formData,
            timestamp: Date.now()
        }));
    }
    
    saveToServer() {
        // Save current form data before sending
        this.collectCurrentStepData();
        
        const dataToSave = {
            ...this.formData,
            current_step: this.currentStep,
            action: 'save'
        };
        
        $.ajax({
            url: 'autosave_wizard.php',
            method: 'POST',
            data: dataToSave,
            success: (response) => {
                if (response.success) {
                    this.showAutoSaveNotification();
                    // Also save to localStorage as backup
                    this.saveToLocalStorage();
                } else {
                    console.error('Server autosave failed:', response.error);
                    // Fallback to localStorage
                    this.saveToLocalStorage();
                }
            },
            error: (xhr, status, error) => {
                console.error('Server autosave error:', error);
                // Fallback to localStorage
                this.saveToLocalStorage();
            }
        });
    }
    
    collectCurrentStepData() {
        // Collect data from current step before saving
        $('#wizard-content input, #wizard-content select, #wizard-content textarea').each((index, element) => {
            const $element = $(element);
            const name = $element.attr('name');
            if (name) {
                if ($element.attr('type') === 'checkbox') {
                    this.formData[name] = $element.is(':checked');
                } else if ($element.attr('type') === 'radio') {
                    if ($element.is(':checked')) {
                        this.formData[name] = $element.val();
                    }
                } else {
                    this.formData[name] = $element.val();
                }
            }
        });
    }
    
    loadSavedProgress() {
        // First try to load from server
        $.ajax({
            url: 'autosave_wizard.php?action=load',
            method: 'GET',
            success: (response) => {
                if (response.success && response.data) {
                    // Server has saved data
                    if (confirm(`Found saved progress from ${response.last_saved}. Would you like to continue from where you left off?`)) {
                        this.formData = { ...this.formData, ...response.data };
                        this.currentStep = response.current_step || 1;
                        this.loadStep(this.currentStep);
                        return;
                    }
                }
                
                // Fallback to localStorage if server doesn't have data or user declined
                this.loadFromLocalStorage();
            },
            error: (xhr, status, error) => {
                console.warn('Failed to load server autosave, falling back to localStorage:', error);
                this.loadFromLocalStorage();
            }
        });
    }
    
    loadFromLocalStorage() {
        const saved = localStorage.getItem('wizard_progress');
        if (saved) {
            const progress = JSON.parse(saved);
            // Load saved progress if it's less than 24 hours old
            if (Date.now() - progress.timestamp < 24 * 60 * 60 * 1000) {
                this.formData = { ...this.formData, ...progress.data };
                // Optionally restore to saved step
                if (confirm('Would you like to continue from where you left off? (Local backup)')) {
                    this.currentStep = progress.step;
                    this.loadStep(this.currentStep);
                }
            }
        }
    }
    
    showAutoSaveNotification() {
        const toastElement = document.getElementById('autosave-toast');
        if (toastElement) {
            const toast = new bootstrap.Toast(toastElement, {
                delay: 2000 // Show for 2 seconds
            });
            toast.show();
        } else {
            // Fallback: show a temporary alert
            this.showNotification('Progress saved automatically', 'success');
        }
    }
    
    showNotification(message, type = 'info') {
        // Create a temporary notification
        const notification = $(`
            <div class="alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('.step-content').prepend(notification);
        setTimeout(() => notification.remove(), 5000);
    }
    
    submitForm() {
        if (this.isSubmitting) return;
        
        this.isSubmitting = true;
        $('#submit-btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');
        
        // Collect final data from current step
        this.collectCurrentStepData();
        
        // Set end time before submission
        this.formData['endTime'] = new Date().toTimeString().split(' ')[0];
        
        // Prepare form data for submission
        $.ajax({
            url: 'submit.php',
            method: 'POST',
            data: this.formData,
            success: (response) => {
                console.log('Submission response:', response);
                this.showCompletionScreen();
                this.clearAutosaveData();
            },
            error: (xhr, status, error) => {
                console.error('Submission error:', xhr.responseText);
                let errorMessage = 'Error submitting form. Please try again.';
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.error) {
                        errorMessage = errorResponse.error;
                    }
                } catch (e) {
                    // If it's not JSON, show the raw response
                    if (xhr.responseText) {
                        errorMessage = 'Server error: ' + xhr.responseText;
                    }
                }
                this.showNotification(errorMessage, 'error');
                this.isSubmitting = false;
                $('#submit-btn').prop('disabled', false).html('<i class="bi bi-check-circle"></i> Submit');
            }
        });
    }
    
    clearAutosaveData() {
        // Clear server autosave
        $.ajax({
            url: 'autosave_wizard.php',
            method: 'POST',
            data: { action: 'clear' },
            success: (response) => {
                console.log('Server autosave cleared');
            },
            error: (xhr, status, error) => {
                console.warn('Failed to clear server autosave:', error);
            }
        });
        
        // Clear localStorage
        localStorage.removeItem('wizard_progress');
    }
    
    showCompletionScreen() {
        $('#wizard-content').html(`
            <div class="completion-animation">
                <div class="completion-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h3 class="mt-3">Parameters Submitted Successfully!</h3>
                <p class="text-muted">Your parameter data has been saved to the system.</p>
                <div class="mt-4">
                    <a href="index.php" class="btn btn-primary me-2">
                        <i class="bi bi-list"></i> View Records
                    </a>
                    <a href="mobile_wizard.php" class="btn btn-outline-primary">
                        <i class="bi bi-plus"></i> Add Another
                    </a>
                </div>
            </div>
        `);
        
        $('.wizard-navigation').hide();
        $('.progress-container').hide();
    }
}

// Global functions
function updateRangeValue(fieldName) {
    const slider = document.getElementById(fieldName);
    const valueDisplay = document.getElementById(fieldName + '-value');
    if (slider && valueDisplay) {
        valueDisplay.textContent = slider.value;
    }
}

function exitWizard() {
    if (confirm('Are you sure you want to exit? Your progress will be saved automatically.')) {
        window.location.href = 'index.php';
    }
}

// Initialize wizard when document is ready
$(document).ready(() => {
    window.wizard = new MobileWizard();
});
