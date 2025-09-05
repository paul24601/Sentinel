<?php
/**
 * Layout Diagnostic Tool
 * Helps identify navbar/sidebar layout issues
 */
session_start();

// Simulate session if not logged in
if (!isset($_SESSION['full_name'])) {
    $_SESSION['full_name'] = 'Test User';
    $_SESSION['role'] = 'admin';
    $_SESSION['id_number'] = 'TEST001';
}

// Include the navbar to test layout
include 'includes/navbar.php';
?>

<style>
/* Diagnostic styles */
.diagnostic-box {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.layout-info {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    border-radius: 0.375rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    font-family: monospace;
    font-size: 0.875rem;
}

.debug-grid {
    background: repeating-linear-gradient(
        90deg,
        #e9ecef,
        #e9ecef 1px,
        transparent 1px,
        transparent 20px
    );
    min-height: 100px;
    padding: 1rem;
}

.measurement-box {
    background: rgba(255, 0, 0, 0.1);
    border: 2px dashed #dc3545;
    padding: 1rem;
    margin: 1rem 0;
}
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4">Layout Diagnostic Tool</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Layout Debug</li>
    </ol>

    <!-- Layout Information -->
    <div class="diagnostic-box">
        <h3>Current Layout Information</h3>
        <div class="layout-info">Body Classes: <span id="bodyClasses"></span></div>
        <div class="layout-info">Sidebar State: <span id="sidebarState"></span></div>
        <div class="layout-info">Content Margin Left: <span id="contentMargin"></span></div>
        <div class="layout-info">Container Padding: <span id="containerPadding"></span></div>
        <div class="layout-info">Viewport Width: <span id="viewportWidth"></span></div>
        <div class="layout-info">Sidebar Width: <span id="sidebarWidth"></span></div>
        <div class="layout-info">Sidebar Position: <span id="sidebarPosition"></span></div>
    </div>

    <!-- Layout Test Grid -->
    <div class="diagnostic-box">
        <h3>Layout Test Grid</h3>
        <p>This grid helps visualize padding and margin issues:</p>
        <div class="debug-grid">
            <div class="measurement-box">
                <h5>Content Area</h5>
                <p>This box should align properly with the sidebar.</p>
                <p>Check for: excessive padding, incorrect margins, overlapping elements.</p>
            </div>
        </div>
    </div>

    <!-- CSS Conflicts Check -->
    <div class="diagnostic-box">
        <h3>CSS Files Loaded</h3>
        <div id="cssFilesList"></div>
    </div>

    <!-- Quick Actions Test -->
    <div class="diagnostic-box">
        <h3>Layout Test Buttons</h3>
        <div class="row g-2">
            <div class="col-12 col-sm-6 col-lg-3">
                <button class="btn btn-primary btn-sm w-100" onclick="toggleSidebar()">
                    <i class="fas fa-bars me-1"></i>Toggle Sidebar
                </button>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <button class="btn btn-success btn-sm w-100" onclick="measureLayout()">
                    <i class="fas fa-ruler me-1"></i>Measure Layout
                </button>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <button class="btn btn-info btn-sm w-100" onclick="highlightElements()">
                    <i class="fas fa-search me-1"></i>Highlight Elements
                </button>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <button class="btn btn-warning btn-sm w-100" onclick="resetLayout()">
                    <i class="fas fa-refresh me-1"></i>Reset Layout
                </button>
            </div>
        </div>
    </div>

    <!-- Problem Areas -->
    <div class="diagnostic-box">
        <h3>Common Problem Areas</h3>
        <div class="alert alert-warning">
            <h5>Potential Issues to Check:</h5>
            <ul>
                <li><strong>Container Padding:</strong> px-4 class adds 1.5rem padding that might conflict with sidebar margins</li>
                <li><strong>Missing Responsive CSS:</strong> responsive-fixes.css may not be loaded</li>
                <li><strong>CSS Load Order:</strong> Conflicting stylesheets loading in wrong order</li>
                <li><strong>Sidebar Toggle State:</strong> JavaScript not properly handling sidebar state</li>
                <li><strong>Mobile Breakpoints:</strong> Layout breaking at specific screen sizes</li>
            </ul>
        </div>
    </div>
</div>

<script>
function updateLayoutInfo() {
    // Get body classes
    document.getElementById('bodyClasses').textContent = document.body.className;
    
    // Check sidebar state
    const sidebarToggled = document.body.classList.contains('sb-sidenav-toggled');
    document.getElementById('sidebarState').textContent = sidebarToggled ? 'Hidden' : 'Visible';
    
    // Get content margin
    const content = document.getElementById('layoutSidenav_content');
    if (content) {
        const styles = window.getComputedStyle(content);
        document.getElementById('contentMargin').textContent = styles.marginLeft;
    }
    
    // Get container padding
    const container = document.querySelector('.container-fluid');
    if (container) {
        const styles = window.getComputedStyle(container);
        document.getElementById('containerPadding').textContent = `Left: ${styles.paddingLeft}, Right: ${styles.paddingRight}`;
    }
    
    // Get viewport width
    document.getElementById('viewportWidth').textContent = window.innerWidth + 'px';
    
    // Get sidebar info
    const sidebar = document.getElementById('layoutSidenav_nav');
    if (sidebar) {
        const styles = window.getComputedStyle(sidebar);
        document.getElementById('sidebarWidth').textContent = styles.width;
        document.getElementById('sidebarPosition').textContent = `Left: ${styles.left}, Position: ${styles.position}`;
    }
    
    // List CSS files
    const cssFiles = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
        .map(link => link.href.split('/').pop())
        .join(', ');
    document.getElementById('cssFilesList').textContent = cssFiles;
}

function toggleSidebar() {
    document.body.classList.toggle('sb-sidenav-toggled');
    updateLayoutInfo();
}

function measureLayout() {
    const measurements = {
        bodyWidth: document.body.offsetWidth,
        sidebarWidth: document.getElementById('layoutSidenav_nav')?.offsetWidth || 0,
        contentWidth: document.getElementById('layoutSidenav_content')?.offsetWidth || 0,
        containerWidth: document.querySelector('.container-fluid')?.offsetWidth || 0
    };
    
    alert(`Layout Measurements:
Body Width: ${measurements.bodyWidth}px
Sidebar Width: ${measurements.sidebarWidth}px
Content Width: ${measurements.contentWidth}px
Container Width: ${measurements.containerWidth}px

Content should be: Body Width - Sidebar Width = ${measurements.bodyWidth - measurements.sidebarWidth}px`);
}

function highlightElements() {
    // Add temporary highlighting
    const elements = [
        document.getElementById('layoutSidenav'),
        document.getElementById('layoutSidenav_nav'),
        document.getElementById('layoutSidenav_content'),
        document.querySelector('.container-fluid'),
        document.querySelector('main')
    ];
    
    elements.forEach((el, index) => {
        if (el) {
            el.style.outline = `3px solid hsl(${index * 60}, 70%, 50%)`;
            el.style.outlineOffset = '2px';
        }
    });
    
    setTimeout(() => {
        elements.forEach(el => {
            if (el) {
                el.style.outline = '';
                el.style.outlineOffset = '';
            }
        });
    }, 3000);
}

function resetLayout() {
    document.body.classList.remove('sb-sidenav-toggled');
    updateLayoutInfo();
}

// Update info on load and resize
window.addEventListener('load', updateLayoutInfo);
window.addEventListener('resize', updateLayoutInfo);

// Monitor for sidebar toggle clicks
document.addEventListener('click', function(e) {
    if (e.target.id === 'sidebarToggle' || e.target.closest('#sidebarToggle')) {
        setTimeout(updateLayoutInfo, 100);
    }
});
</script>

<?php include 'includes/navbar_close.php'; ?>
