// Session timeout handling
let sessionTimeout;
const TIMEOUT_DURATION = 30 * 60 * 1000; // 30 minutes in milliseconds
const WARNING_DURATION = 5 * 60 * 1000; // 5 minutes warning before timeout

function resetSessionTimeout() {
    clearTimeout(sessionTimeout);
    sessionTimeout = setTimeout(showTimeoutWarning, TIMEOUT_DURATION - WARNING_DURATION);
}

function showTimeoutWarning() {
    // Create and show the warning modal
    const modalHtml = `
        <div class="modal fade" id="sessionTimeoutModal" tabindex="-1" role="dialog" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
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
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    // Show the modal
    $('#sessionTimeoutModal').modal('show');

    // Set timeout for actual logout
    setTimeout(logout, WARNING_DURATION);
}

function extendSession() {
    // Make an AJAX call to refresh the session
    $.ajax({
        url: '../refresh_session.php',
        method: 'POST',
        success: function(response) {
            $('#sessionTimeoutModal').modal('hide');
            resetSessionTimeout();
        },
        error: function() {
            alert('Failed to extend session. Please try again.');
        }
    });
}

function logout() {
    window.location.href = '../login.html';
}

// Reset timeout on user activity
document.addEventListener('mousemove', resetSessionTimeout);
document.addEventListener('keypress', resetSessionTimeout);
document.addEventListener('click', resetSessionTimeout);
document.addEventListener('scroll', resetSessionTimeout);

// Initialize timeout on page load
resetSessionTimeout(); 