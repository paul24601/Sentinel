            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; 2025 Sentinel OJT</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Notifications Modal -->
    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationsModalLabel">
                        <i class="fas fa-bell me-2"></i>Notifications
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="notificationsList">
                        <?php
                        // Get notifications for modal display
                        if (isset($_SESSION['id_number'])) {
                            require_once __DIR__ . '/admin_notifications.php';
                            $modal_notifications = getAdminNotifications($_SESSION['id_number'], $_SESSION['role']);
                            
                            if (!empty($modal_notifications)): ?>
                                <?php foreach ($modal_notifications as $notification): ?>
                                    <div class="notification-item mb-3 p-3 border rounded <?php echo !$notification['is_viewed'] ? 'bg-light' : ''; ?>">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <i class="<?php echo getNotificationIcon($notification['notification_type']); ?> fa-lg"></i>
                                                <?php if ($notification['is_urgent']): ?>
                                                    <span class="badge bg-danger ms-1">Urgent</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($notification['title']); ?></h6>
                                                <p class="mb-2"><?php echo nl2br(htmlspecialchars($notification['message'])); ?></p>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo timeAgo($notification['created_at']); ?>
                                                </small>
                                                <?php if (!$notification['is_viewed']): ?>
                                                    <button class="btn btn-sm btn-outline-primary ms-2" 
                                                            onclick="markAsViewed(<?php echo $notification['id']; ?>)">
                                                        Mark as Read
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                    <p>No notifications available</p>
                                </div>
                            <?php endif; ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo $basePath; ?>admin/notifications.php" class="btn btn-primary">
                            <i class="fas fa-cog me-1"></i>Manage Notifications
                        </a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SB Admin JavaScript (load first like DMS) -->
    <?php
    $basePath = '';
    $currentDir = dirname($_SERVER['SCRIPT_NAME']);
    if (strpos($currentDir, '/dms') !== false || strpos($currentDir, '/parameters') !== false || 
        strpos($currentDir, '/admin') !== false || strpos($currentDir, '/production_report') !== false) {
        $basePath = '../';
    }
    ?>
    
    <!-- Load all JavaScript at the bottom for optimal performance -->
    
    <!-- FontAwesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
    
    <!-- Bootstrap JS Bundle - AFTER jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SB Admin Scripts -->
    <script src="<?php echo $basePath; ?>js/scripts.js"></script>
    
    <!-- DataTables JavaScript - AFTER jQuery and Bootstrap -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- Additional libraries for export functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Universal DataTables Configuration -->
    <script src="<?php echo $basePath; ?>js/datatables-universal.js"></script>
    
    <!-- Enhanced initialization script -->
    <script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing Bootstrap components...');
        
        // Initialize all Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Initialize all Bootstrap dropdowns manually
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
        
        console.log('Bootstrap components initialized:', dropdownList.length, 'dropdowns found');
        
        // Test dropdown functionality
        dropdownElementList.forEach(function(element) {
            element.addEventListener('click', function(e) {
                console.log('Dropdown clicked:', element.id);
            });
        });
    });

    // Enhanced notification management
    function markAsViewed(notificationId) {
        fetch('<?php echo $basePath; ?>includes/mark_notification_viewed.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'notification_id=' + notificationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update notification badge
                updateNotificationBadge();
                
                // Remove the "New" badge from the notification item
                const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notificationItem) {
                    const newBadge = notificationItem.querySelector('.badge');
                    if (newBadge) {
                        newBadge.remove();
                    }
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Show notification modal with specific notification details
    function showNotificationModal(notificationId) {
        // Mark as viewed first
        markAsViewed(notificationId);
        
        // Fetch notification details and show modal
        fetch('<?php echo $basePath; ?>includes/get_notification_details.php?id=' + notificationId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = data.notification;
                
                // Update modal content
                document.getElementById('notificationsModalLabel').innerHTML = 
                    `<i class="${getNotificationIconClass(notification.notification_type)} me-2"></i>${notification.title}`;
                
                document.getElementById('notificationsList').innerHTML = `
                    <div class="notification-detail">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3">
                                <i class="${getNotificationIconClass(notification.notification_type)} fa-2x"></i>
                                ${notification.is_urgent ? '<span class="badge bg-danger ms-2">Urgent</span>' : ''}
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2">${notification.title}</h5>
                                <div class="notification-message">
                                    ${notification.message.replace(/\n/g, '<br>')}
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        ${formatDateTime(notification.created_at)}
                                    </small>
                                    <small class="text-muted ms-3">
                                        <i class="fas fa-user me-1"></i>
                                        Created by: ${notification.created_by}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('notificationsModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback to showing all notifications modal
            showAllNotifications();
        });
    }

    // Show all notifications modal
    function showAllNotifications() {
        const modal = new bootstrap.Modal(document.getElementById('notificationsModal'));
        modal.show();
    }

    // Helper functions
    function getNotificationIconClass(type) {
        switch (type) {
            case 'warning': return 'fas fa-exclamation-triangle text-warning';
            case 'danger': return 'fas fa-exclamation-circle text-danger';
            case 'success': return 'fas fa-check-circle text-success';
            case 'info':
            default: return 'fas fa-info-circle text-primary';
        }
    }

    function formatDateTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' at ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }

    function updateNotificationBadge() {
        // Simple badge update - refresh count
        fetch('<?php echo $basePath; ?>includes/get_notification_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('#notificationDropdown .badge');
            if (data.count > 0) {
                if (badge) {
                    badge.textContent = data.count;
                }
            } else {
                if (badge) {
                    badge.remove();
                }
            }
        })
        .catch(error => console.error('Error updating badge:', error));
    }
    </script>
    
</body>
</html>
