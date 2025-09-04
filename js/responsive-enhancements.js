/*!
 * Sentinel Responsive Enhancements
 * Mobile-first JavaScript improvements for better user experience
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. ENHANCED MOBILE SIDEBAR BEHAVIOR - COMPLETELY FIXED
    // ==========================================
    
    const body = document.body;
    const sidebarToggle = document.querySelector('#sidebarToggle');
    const layoutSidenav = document.querySelector('#layoutSidenav');
    const layoutSidenavContent = document.querySelector('#layoutSidenav_content');
    
    // FORCE HIDE sidebar on medium screens (768px - 991px) and mobile
    function handleSidebarDisplay() {
        const windowWidth = window.innerWidth;
        
        if (windowWidth <= 991.98) {
            // On mobile and medium screens: hide sidebar by default
            body.classList.remove('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', 'false');
        }
    }
    
    // Initial call
    handleSidebarDisplay();
    
    // Auto-close sidebar when clicking outside on mobile - FIXED VERSION
    if (layoutSidenavContent) {
        layoutSidenavContent.addEventListener('click', function(e) {
            // Only close if sidebar is open and we're on mobile/medium screen
            if (window.innerWidth <= 991.98 && body.classList.contains('sb-sidenav-toggled')) {
                body.classList.remove('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', 'false');
            }
        });
        
        // Prevent clicks inside sidebar from bubbling up and ensure navigation works
        const sidebar = document.querySelector('#layoutSidenav_nav');
        if (sidebar) {
            sidebar.addEventListener('click', function(e) {
                // Don't stop propagation for nav links - let them navigate
                if (!e.target.closest('.nav-link')) {
                    e.stopPropagation();
                }
            });
        }
        
        // Ensure nav links are always clickable
        const navLinks = document.querySelectorAll('.sb-sidenav .nav-link');
        navLinks.forEach(function(link) {
            link.style.pointerEvents = 'auto';
            link.style.zIndex = '1032';
        });
    }
    
    // ==========================================
    // 2. RESPONSIVE TABLE IMPROVEMENTS
    // ==========================================
    
    // Add horizontal scroll indicators for tables
    function addTableScrollIndicators() {
        const tableResponsive = document.querySelectorAll('.table-responsive');
        
        tableResponsive.forEach(function(container) {
            const table = container.querySelector('table');
            if (table && table.scrollWidth > container.clientWidth) {
                container.classList.add('has-scroll');
                
                // Add scroll indicator
                if (!container.querySelector('.scroll-indicator')) {
                    const indicator = document.createElement('div');
                    indicator.className = 'scroll-indicator';
                    indicator.innerHTML = '<i class="fas fa-arrows-alt-h"></i> Scroll horizontally to see more';
                    container.appendChild(indicator);
                }
            }
        });
    }
    
    // ==========================================
    // 3. FORM IMPROVEMENTS FOR MOBILE
    // ==========================================
    
    // Auto-focus first input on larger screens, but not on mobile (to prevent virtual keyboard)
    if (window.innerWidth > 767.98) {
        const firstInput = document.querySelector('form input:not([type="hidden"]), form select, form textarea');
        if (firstInput && !firstInput.hasAttribute('readonly')) {
            firstInput.focus();
        }
    }
    
    // Improve select dropdowns on mobile
    const selects = document.querySelectorAll('select.form-control, select.form-select');
    selects.forEach(function(select) {
        if (window.innerWidth <= 767.98) {
            select.style.fontSize = '16px'; // Prevents zoom on iOS
        }
    });
    
    // ==========================================
    // 4. TOUCH-FRIENDLY IMPROVEMENTS
    // ==========================================
    
    // Add touch-friendly classes to buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(function(btn) {
        if (window.innerWidth <= 767.98) {
            btn.classList.add('btn-touch');
        }
    });
    
    // Improve dropdown behavior on touch devices
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(function(dropdown) {
        if ('ontouchstart' in window) {
            dropdown.addEventListener('touchstart', function(e) {
                e.preventDefault();
                const dropdownInstance = new bootstrap.Dropdown(dropdown);
                dropdownInstance.toggle();
            });
        }
    });
    
    // ==========================================
    // 5. VIEWPORT AND ORIENTATION HANDLING
    // ==========================================
    
    // Handle orientation changes
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            // Recalculate table indicators
            addTableScrollIndicators();
            
            // Close sidebar on orientation change to landscape on mobile
            if (window.innerWidth <= 991.98 && Math.abs(window.orientation) === 90) {
                body.classList.remove('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', 'false');
            }
        }, 150);
    });
    
    // Handle window resize - IMPROVED FOR MEDIUM SCREENS
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            addTableScrollIndicators();
            
            // Handle sidebar display based on screen size
            const windowWidth = window.innerWidth;
            
            if (windowWidth <= 991.98) {
                // On mobile and medium screens: ALWAYS hide sidebar
                body.classList.remove('sb-sidenav-toggled');
                localStorage.setItem('sb|sidebar-toggle', 'false');
            } else {
                // On desktop: restore sidebar if it was previously open
                const sidebarState = localStorage.getItem('sb|sidebar-toggle');
                if (sidebarState === 'true') {
                    body.classList.add('sb-sidenav-toggled');
                }
            }
        }, 250);
    });
    
    // ==========================================
    // 6. DATATABLES RESPONSIVE ENHANCEMENTS
    // ==========================================
    
    // Enhance DataTables for mobile
    if (typeof DataTable !== 'undefined' || typeof $.fn.DataTable !== 'undefined') {
        // Wait for DataTables to initialize, then apply mobile enhancements
        setTimeout(function() {
            const dataTables = document.querySelectorAll('.dataTable');
            dataTables.forEach(function(table) {
                if (window.innerWidth <= 767.98) {
                    // Add mobile-specific classes
                    table.classList.add('table-mobile');
                    
                    // Improve pagination for mobile
                    const pagination = table.closest('.dataTables_wrapper').querySelector('.dataTables_paginate');
                    if (pagination) {
                        pagination.classList.add('pagination-mobile');
                    }
                }
            });
        }, 500);
    }
    
    // ==========================================
    // 7. NOTIFICATION IMPROVEMENTS
    // ==========================================
    
    // Close notifications dropdown when clicking outside on mobile
    const notificationDropdown = document.querySelector('#notifDropdown');
    if (notificationDropdown && window.innerWidth <= 767.98) {
        document.addEventListener('click', function(e) {
            const dropdown = notificationDropdown.closest('.dropdown');
            if (!dropdown.contains(e.target)) {
                const dropdownInstance = bootstrap.Dropdown.getInstance(notificationDropdown);
                if (dropdownInstance) {
                    dropdownInstance.hide();
                }
            }
        });
    }
    
    // ==========================================
    // 8. ACCESSIBILITY IMPROVEMENTS
    // ==========================================
    
    // Add skip to main content link
    if (!document.querySelector('.skip-link')) {
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.className = 'skip-link';
        skipLink.textContent = 'Skip to main content';
        skipLink.style.cssText = `
            position: absolute;
            top: -40px;
            left: 6px;
            background: #000;
            color: #fff;
            padding: 8px;
            text-decoration: none;
            z-index: 9999;
            border-radius: 4px;
            transition: top 0.3s;
        `;
        
        skipLink.addEventListener('focus', function() {
            this.style.top = '6px';
        });
        
        skipLink.addEventListener('blur', function() {
            this.style.top = '-40px';
        });
        
        document.body.insertBefore(skipLink, document.body.firstChild);
        
        // Add id to main content if it doesn't exist
        const main = document.querySelector('main');
        if (main && !main.id) {
            main.id = 'main-content';
        }
    }
    
    // ==========================================
    // 9. PERFORMANCE OPTIMIZATIONS
    // ==========================================
    
    // Lazy load images if any exist
    const images = document.querySelectorAll('img[data-src]');
    if (images.length > 0 && 'IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(function(img) {
            imageObserver.observe(img);
        });
    }
    
    // ==========================================
    // 10. INITIALIZE ENHANCEMENTS - WITH CSS BACKUP
    // ==========================================
    
    // CSS backup to ensure sidebar is always clickable
    const styleElement = document.createElement('style');
    styleElement.textContent = `
        @media (max-width: 991.98px) {
            .sb-sidenav .nav-link {
                pointer-events: auto !important;
                z-index: 1032 !important;
                position: relative !important;
            }
            
            .sb-sidenav {
                pointer-events: auto !important;
                z-index: 1031 !important;
            }
            
            /* Remove any remaining overlays */
            .sb-sidenav-toggled::before,
            .sb-sidenav-toggled::after {
                display: none !important;
            }
        }
    `;
    document.head.appendChild(styleElement);
    
    // Initial setup
    addTableScrollIndicators();
    
    // Add mobile class to body for CSS targeting
    if (window.innerWidth <= 767.98) {
        body.classList.add('mobile-device');
    }
    
    // Add touch class for touch devices
    if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
        body.classList.add('touch-device');
    }
    
});

// ==========================================
// CSS ADDITIONS FOR JAVASCRIPT FEATURES
// ==========================================

// Add CSS for scroll indicators and mobile improvements
const style = document.createElement('style');
style.textContent = `
    /* Table scroll indicators */
    .table-responsive.has-scroll {
        position: relative;
    }
    
    .scroll-indicator {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.75rem;
        color: #6c757d;
        background: rgba(255, 255, 255, 0.9);
        padding: 2px 8px;
        border-radius: 4px;
        white-space: nowrap;
        z-index: 10;
    }
    
    /* Touch-friendly buttons */
    .btn-touch {
        min-height: 44px;
        padding: 0.75rem 1rem;
    }
    
    /* Mobile table improvements */
    .table-mobile {
        font-size: 0.8rem;
    }
    
    .table-mobile th,
    .table-mobile td {
        padding: 0.5rem 0.25rem;
        vertical-align: middle;
    }
    
    /* Mobile pagination */
    .pagination-mobile .paginate_button {
        padding: 0.5rem;
        margin: 0 1px;
        font-size: 0.8rem;
    }
    
    /* Mobile device specific styles */
    .mobile-device .card-body {
        padding: 1rem 0.75rem;
    }
    
    .mobile-device .btn {
        font-size: 0.9rem;
    }
    
    .mobile-device .form-control,
    .mobile-device .form-select {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    /* Touch device specific styles */
    .touch-device .btn:hover {
        transform: none; /* Disable hover effects on touch devices */
    }
    
    /* Skip link styles */
    .skip-link:focus {
        top: 6px !important;
    }
    
    /* Fixed sidebar mobile behavior */
    @media (max-width: 991.98px) {
        #layoutSidenav_nav {
            pointer-events: auto !important;
            z-index: 1050 !important;
        }
        
        .sb-sidenav .nav-link {
            pointer-events: auto !important;
            cursor: pointer !important;
        }
    }
`;

document.head.appendChild(style);
