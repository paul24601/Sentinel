/**
 * Session Keep-Alive Script
 * Prevents session timeout while users are actively filling forms
 */

(function() {
    'use strict';
    
    let sessionWarningShown = false;
    let sessionTimeout;
    let warningTimeout;
    
    // Session configuration (30 minutes = 1800 seconds)
    const SESSION_DURATION = 30 * 60 * 1000; // 30 minutes in milliseconds
    const WARNING_TIME = 5 * 60 * 1000; // Show warning 5 minutes before expiry
    const REFRESH_INTERVAL = 5 * 60 * 1000; // Refresh session every 5 minutes when active
    
    // Track user activity
    let lastActivity = Date.now();
    let isUserActive = true;
    
    // Activity events to monitor
    const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click', 'focus'];
    
    function updateActivity() {
        lastActivity = Date.now();
        isUserActive = true;
        
        // Clear existing timeouts
        clearTimeout(sessionTimeout);
        clearTimeout(warningTimeout);
        
        // Reset session warning
        sessionWarningShown = false;
        hideSessionWarning();
        
        // Set new timeouts
        setSessionTimeouts();
    }
    
    function setSessionTimeouts() {
        // Show warning before session expires
        warningTimeout = setTimeout(showSessionWarning, SESSION_DURATION - WARNING_TIME);
        
        // Auto-logout when session expires
        sessionTimeout = setTimeout(handleSessionTimeout, SESSION_DURATION);
    }
    
    function showSessionWarning() {
        if (sessionWarningShown) return;
        
        sessionWarningShown = true;
        
        // Create warning modal
        const warningDiv = document.createElement('div');
        warningDiv.id = 'session-warning';
        warningDiv.innerHTML = `
            <div class="modal fade show" tabindex="-1" style="display: block; background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle"></i> Session Expiring Soon
                            </h5>
                        </div>
                        <div class="modal-body">
                            <p>Your session will expire in <span id="countdown">5:00</span> minutes.</p>
                            <p>Click "Continue Working" to extend your session, or your work may be lost.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="refreshSession()">
                                <i class="fas fa-refresh"></i> Continue Working
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="logout()">
                                <i class="fas fa-sign-out-alt"></i> Logout Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(warningDiv);
        
        // Start countdown
        startCountdown();
    }
    
    function hideSessionWarning() {
        const warningDiv = document.getElementById('session-warning');
        if (warningDiv) {
            warningDiv.remove();
        }
    }
    
    function startCountdown() {
        let timeLeft = 5 * 60; // 5 minutes in seconds
        
        const countdownInterval = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const countdownElement = document.getElementById('countdown');
            
            if (countdownElement) {
                countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
            
            timeLeft--;
            
            if (timeLeft < 0 || !sessionWarningShown) {
                clearInterval(countdownInterval);
            }
        }, 1000);
    }
    
    function handleSessionTimeout() {
        alert('Your session has expired. You will be redirected to the login page.');
        window.location.href = '../login.html?error=' + encodeURIComponent('Session expired. Please log in again.');
    }
    
    // Refresh session via AJAX
    window.refreshSession = function() {
        fetch('refresh_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                hideSessionWarning();
                updateActivity();
                console.log('Session refreshed successfully');
            } else {
                throw new Error('Session refresh failed');
            }
        })
        .catch(error => {
            console.error('Session refresh error:', error);
            alert('Unable to refresh session. Please save your work and log in again.');
        });
    };
    
    // Logout function
    window.logout = function() {
        window.location.href = '../logout.php';
    };
    
    // Auto-refresh session for active users
    function autoRefreshSession() {
        if (isUserActive && (Date.now() - lastActivity) < REFRESH_INTERVAL) {
            refreshSession();
        }
        isUserActive = false; // Reset activity flag
    }
    
    // Initialize session management
    function initializeSessionManagement() {
        // Add activity listeners
        activityEvents.forEach(event => {
            document.addEventListener(event, updateActivity, true);
        });
        
        // Set initial timeouts
        setSessionTimeouts();
        
        // Auto-refresh session for active users every 5 minutes
        setInterval(autoRefreshSession, REFRESH_INTERVAL);
        
        // Warn user before leaving page with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            const forms = document.querySelectorAll('form');
            let hasUnsavedChanges = false;
            
            forms.forEach(form => {
                const formData = new FormData(form);
                for (let [key, value] of formData.entries()) {
                    if (value && value.toString().trim() !== '') {
                        hasUnsavedChanges = true;
                        break;
                    }
                }
            });
            
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                return e.returnValue;
            }
        });
    }
    
    // Initialize when DOM is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeSessionManagement);
    } else {
        initializeSessionManagement();
    }
    
})();
