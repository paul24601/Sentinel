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
                title: 'Product & Machine Info',
                description: 'Basic product and machine details',
                fields: [
                    { name: 'process_no', label: 'Process Number', type: 'text', required: true },
                    { name: 'product_description', label: 'Product Description', type: 'text', required: true },
                    { name: 'shot_count', label: 'Shot Count', type: 'number', required: true },
                    { name: 'machine_no', label: 'Machine Number', type: 'select', options: ['Machine 1', 'Machine 2', 'Machine 3'], required: true },
                    { name: 'injection_machine', label: 'Injection Machine', type: 'text', required: true }
                ]
            },
            2: {
                title: 'Product Details',
                description: 'Detailed product specifications',
                fields: [
                    { name: 'part_number', label: 'Part Number', type: 'text', required: true },
                    { name: 'part_description', label: 'Part Description', type: 'textarea', required: true },
                    { name: 'core_cavity', label: 'Core/Cavity', type: 'text' },
                    { name: 'tolerance', label: 'Tolerance', type: 'text' }
                ]
            },
            3: {
                title: 'Material Composition',
                description: 'Material specifications - at least Material 1 is required',
                fields: 'materials',
                multiple: true
            },
            4: {
                title: 'Colorant Details',
                description: 'Color specifications (Optional)',
                optional: true,
                fields: [
                    { name: 'colorant', label: 'Colorant', type: 'text' },
                    { name: 'colorantColor', label: 'Color', type: 'text' },
                    { name: 'colorant-dosage', label: 'Colorant Dosage', type: 'text' },
                    { name: 'colorant-stabilizer', label: 'Colorant Stabilizer', type: 'text' },
                    { name: 'colorant-stabilizer-dosage', label: 'Stabilizer Dosage (grams)', type: 'text' }
                ]
            },
            5: {
                title: 'Mold & Operation Specs',
                description: 'Mold and operation specifications',
                fields: [
                    { name: 'mold-code', label: 'Mold Code', type: 'text', required: true },
                    { name: 'clamping-force', label: 'Clamping Force', type: 'text', required: true },
                    { name: 'shot-size', label: 'Shot Size', type: 'number', required: true },
                    { name: 'cycle-time', label: 'Cycle Time (seconds)', type: 'number', required: true }
                ]
            },
            6: {
                title: 'Timer Parameters',
                description: 'Timing specifications',
                fields: [
                    { name: 'injection-time', label: 'Injection Time', type: 'time' },
                    { name: 'packing-time', label: 'Packing Time', type: 'time' },
                    { name: 'cooling-time', label: 'Cooling Time', type: 'time' },
                    { name: 'core-pull-timer', label: 'Core Pull Timer', type: 'time' }
                ]
            },
            7: {
                title: 'Barrel Heater Temperatures',
                description: 'Temperature settings for barrel heaters',
                fields: [
                    { name: 'nozzle-temp', label: 'Nozzle Temperature (°C)', type: 'range', min: 0, max: 400 },
                    { name: 'barrel-temp-1', label: 'Barrel 1 Temperature (°C)', type: 'range', min: 0, max: 400 },
                    { name: 'barrel-temp-2', label: 'Barrel 2 Temperature (°C)', type: 'range', min: 0, max: 400 },
                    { name: 'barrel-temp-3', label: 'Barrel 3 Temperature (°C)', type: 'range', min: 0, max: 400 }
                ]
            },
            8: {
                title: 'Mold Heater Temperatures',
                description: 'Temperature settings for mold heaters',
                fields: [
                    { name: 'mold-temp-1', label: 'Mold Temperature 1 (°C)', type: 'range', min: 0, max: 200 },
                    { name: 'mold-temp-2', label: 'Mold Temperature 2 (°C)', type: 'range', min: 0, max: 200 },
                    { name: 'mold-temp-3', label: 'Mold Temperature 3 (°C)', type: 'range', min: 0, max: 200 }
                ]
            },
            9: {
                title: 'Injection Parameters',
                description: 'Injection process settings',
                fields: [
                    { name: 'injection-pressure', label: 'Injection Pressure (bar)', type: 'range', min: 0, max: 2000 },
                    { name: 'injection-speed', label: 'Injection Speed (%)', type: 'range', min: 0, max: 100 },
                    { name: 'packing-pressure', label: 'Packing Pressure (bar)', type: 'range', min: 0, max: 1500 },
                    { name: 'back-pressure', label: 'Back Pressure (bar)', type: 'range', min: 0, max: 200 }
                ]
            },
            10: {
                title: 'Mold Parameters',
                description: 'Mold opening and closing settings',
                fields: [
                    { name: 'mold-close-speed', label: 'Mold Close Speed (%)', type: 'range', min: 0, max: 100 },
                    { name: 'mold-open-speed', label: 'Mold Open Speed (%)', type: 'range', min: 0, max: 100 },
                    { name: 'ejection-speed', label: 'Ejection Speed (%)', type: 'range', min: 0, max: 100 }
                ]
            },
            11: {
                title: 'Additional Information',
                description: 'Extra notes and specifications',
                fields: [
                    { name: 'remarks', label: 'Remarks', type: 'textarea' },
                    { name: 'special-instructions', label: 'Special Instructions', type: 'textarea' },
                    { name: 'quality-notes', label: 'Quality Notes', type: 'textarea' }
                ]
            },
            12: {
                title: 'Personnel & Review',
                description: 'Personnel information and final review',
                fields: [
                    { name: 'operator-name', label: 'Operator Name', type: 'text', required: true },
                    { name: 'inspector-name', label: 'Inspector Name', type: 'text' },
                    { name: 'shift', label: 'Shift', type: 'select', options: ['Day Shift', 'Night Shift'], required: true }
                ]
            }
        };
        
        return steps[stepNumber];
    }
    
    generateStepHTML(stepData) {
        let html = `
            <div class="step-card">
                <div class="step-header">
                    <h3>${stepData.title}</h3>
                    <p class="step-description">${stepData.description}</p>
                    ${stepData.optional ? '<span class="optional-badge">Optional</span>' : ''}
                </div>
                <div class="step-content">
        `;
        
        if (stepData.fields === 'materials') {
            html += this.generateMaterialsHTML();
        } else if (Array.isArray(stepData.fields)) {
            stepData.fields.forEach(field => {
                html += this.generateFieldHTML(field);
            });
        }
        
        html += `
                </div>
            </div>
        `;
        
        return html;
    }
    
    generateFieldHTML(field) {
        let html = `<div class="form-group">`;
        html += `<label for="${field.name}" class="form-label">`;
        html += `${field.label}`;
        if (field.required) html += ` <span class="text-danger">*</span>`;
        html += `</label>`;
        
        switch (field.type) {
            case 'text':
            case 'number':
                html += `<input type="${field.type}" class="form-control" id="${field.name}" name="${field.name}" ${field.required ? 'required' : ''}>`;
                break;
                
            case 'textarea':
                html += `<textarea class="form-control" id="${field.name}" name="${field.name}" rows="3" ${field.required ? 'required' : ''}></textarea>`;
                break;
                
            case 'select':
                html += `<select class="form-select" id="${field.name}" name="${field.name}" ${field.required ? 'required' : ''}>`;
                html += `<option value="">Choose...</option>`;
                field.options.forEach(option => {
                    html += `<option value="${option}">${option}</option>`;
                });
                html += `</select>`;
                break;
                
            case 'time':
                html += `<input type="time" class="form-control" id="${field.name}" name="${field.name}">`;
                break;
                
            case 'range':
                html += `
                    <div class="range-container">
                        <input type="range" class="range-slider" id="${field.name}" name="${field.name}" 
                               min="${field.min}" max="${field.max}" value="${field.min}" 
                               oninput="updateRangeValue('${field.name}')">
                        <div class="range-value" id="${field.name}-value">${field.min}</div>
                    </div>
                `;
                break;
        }
        
        html += `<div class="invalid-feedback"></div>`;
        html += `</div>`;
        
        return html;
    }
    
    generateMaterialsHTML() {
        let html = '';
        for (let i = 1; i <= 4; i++) {
            const isRequired = i === 1;
            html += `
                <div class="material-card ${i === 1 ? 'active' : ''}" data-material="${i}">
                    <h6>Material ${i} ${isRequired ? '<span class="text-danger">*</span>' : '<span class="optional-badge">Optional</span>'}</h6>
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <label for="type${i}" class="form-label">Type</label>
                            <input type="text" class="form-control" id="type${i}" name="type${i}" ${isRequired ? 'required' : ''}>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="brand${i}" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand${i}" name="brand${i}" ${isRequired ? 'required' : ''}>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <label for="mix${i}" class="form-label">Mixture (%)</label>
                            <input type="number" step="any" class="form-control" id="mix${i}" name="mix${i}" ${isRequired ? 'required' : ''}>
                        </div>
                    </div>
                </div>
            `;
        }
        return html;
    }
    
    nextStep() {
        if (this.isSubmitting) return;
        
        if (this.validateCurrentStep()) {
            this.currentStep++;
            this.loadStep(this.currentStep);
        }
    }
    
    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.loadStep(this.currentStep);
        }
    }
    
    validateCurrentStep() {
        let isValid = true;
        const requiredFields = $('#wizard-content').find('[required]');
        
        requiredFields.each((index, field) => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        
        // Special validation for materials step
        if (this.currentStep === 3) {
            const material1Valid = $('#type1').val() && $('#brand1').val() && $('#mix1').val();
            if (!material1Valid) {
                this.showNotification('Material 1 is required', 'error');
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    validateField(field) {
        const $field = $(field);
        const value = $field.val().trim();
        const isRequired = $field.prop('required');
        
        if (isRequired && !value) {
            $field.addClass('is-invalid').removeClass('is-valid');
            $field.siblings('.invalid-feedback').text('This field is required');
            return false;
        } else if (value || !isRequired) {
            $field.addClass('is-valid').removeClass('is-invalid');
            $field.siblings('.invalid-feedback').text('');
            return true;
        }
        
        return true;
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
    
    updateProgress() {
        const percentage = (this.currentStep / this.totalSteps) * 100;
        $('#progress-bar').css('width', `${percentage}%`);
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
        
        // Prepare form data for submission
        $.ajax({
            url: 'submit_wizard_clean.php',
            method: 'POST',
            data: this.formData,
            success: (response) => {
                if (response.success) {
                    this.showCompletionScreen();
                    // Clear both server and local autosave data
                    this.clearAutosaveData();
                } else {
                    this.showNotification(response.error || 'Error submitting form. Please try again.', 'error');
                    this.isSubmitting = false;
                    $('#submit-btn').prop('disabled', false).html('<i class="bi bi-check-circle"></i> Submit');
                }
            },
            error: (xhr, status, error) => {
                let errorMessage = 'Error submitting form. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
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
