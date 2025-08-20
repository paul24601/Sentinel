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
    <script src="<?php echo $basePath; ?>js/scripts.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    
    <!-- DataTables JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    
    <!-- Bootstrap JS and Popper.js (separate like DMS) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
