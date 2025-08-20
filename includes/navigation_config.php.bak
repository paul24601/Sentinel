<?php
/**
 * Navigation Configuration
 * Centralized navigation structure and role-based permissions
 * 
 * @author Sentinel Development Team
 * @date August 20, 2025
 */

// Define role permissions
$role_permissions = [
    'admin' => [
        'dashboard' => true,
        'dms' => ['view', 'create', 'edit', 'delete', 'approve'],
        'parameters' => ['view', 'create', 'edit', 'delete'],
        'production' => ['view', 'create', 'edit', 'delete', 'analytics'],
        'admin' => ['users', 'parameters', 'notifications', 'password_management', 'analytics'],
        'sensory' => ['view', 'manage', 'controls']
    ],
    'supervisor' => [
        'dashboard' => true,
        'dms' => ['view', 'create', 'edit', 'approve'],
        'parameters' => ['view', 'create', 'edit'],
        'production' => ['view', 'create', 'analytics'],
        'admin' => [],
        'sensory' => []
    ],
    'personnel' => [
        'dashboard' => true,
        'dms' => ['view', 'create'],
        'parameters' => ['view', 'create'],
        'production' => [],
        'admin' => [],
        'sensory' => []
    ],
    'Quality Control Inspection' => [
        'dashboard' => true,
        'dms' => ['view'],
        'parameters' => ['view'],
        'production' => [],
        'admin' => [],
        'sensory' => []
    ]
];

// Define navigation structure
$navigation_structure = [
    'brand' => [
        'name' => 'Sentinel OJT',
        'url' => '/index.php',
        'logo' => '/logo.png'
    ],
    
    'modules' => [
        'dashboard' => [
            'title' => 'Dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'url' => '/index.php',
            'roles' => ['admin', 'supervisor', 'personnel', 'Quality Control Inspection']
        ],
        
        'dms' => [
            'title' => 'DMS',
            'icon' => 'fas fa-people-roof',
            'roles' => ['admin', 'supervisor', 'personnel', 'Quality Control Inspection'],
            'submenu' => [
                'entry' => [
                    'title' => 'Data Entry',
                    'url' => '/dms/index.php',
                    'roles' => ['admin', 'supervisor', 'personnel']
                ],
                'records' => [
                    'title' => 'Records',
                    'url' => '/dms/submission.php',
                    'roles' => ['admin', 'supervisor', 'personnel', 'Quality Control Inspection']
                ],
                'analytics' => [
                    'title' => 'Analytics',
                    'url' => '/dms/analytics.php',
                    'roles' => ['admin', 'supervisor', 'personnel']
                ],
                'approval' => [
                    'title' => 'Approvals',
                    'url' => '/dms/approval.php',
                    'roles' => ['admin', 'supervisor']
                ],
                'declined' => [
                    'title' => 'Declined Submissions',
                    'url' => '/dms/declined_submissions.php',
                    'roles' => ['admin', 'supervisor', 'personnel']
                ]
            ]
        ],
        
        'parameters' => [
            'title' => 'Parameters',
            'icon' => 'fas fa-columns',
            'roles' => ['admin', 'supervisor', 'personnel', 'Quality Control Inspection'],
            'submenu' => [
                'entry' => [
                    'title' => 'Data Entry',
                    'url' => '/parameters/index.php',
                    'roles' => ['admin', 'supervisor', 'personnel']
                ],
                'visualization' => [
                    'title' => 'Data Visualization',
                    'url' => '/parameters/submission.php',
                    'roles' => ['admin', 'supervisor', 'personnel', 'Quality Control Inspection']
                ],
                'analytics' => [
                    'title' => 'Data Analytics',
                    'url' => '/parameters/analytics.php',
                    'roles' => ['admin', 'supervisor', 'personnel']
                ]
            ]
        ],
        
        'production' => [
            'title' => 'Production Reports',
            'icon' => 'fas fa-sheet-plastic',
            'roles' => ['admin', 'supervisor'],
            'submenu' => [
                'entry' => [
                    'title' => 'Data Entry',
                    'url' => '/production_report/index.php',
                    'roles' => ['admin', 'supervisor']
                ],
                'reports' => [
                    'title' => 'Reports',
                    'url' => '/production_report/submit.php',
                    'roles' => ['admin', 'supervisor']
                ],
                'analytics' => [
                    'title' => 'Analytics',
                    'url' => '/production_report/analytics.php',
                    'roles' => ['admin']
                ]
            ]
        ],
        
        'sensory' => [
            'title' => 'Sensory Data',
            'icon' => 'fas fa-microchip',
            'roles' => ['admin'],
            'submenu' => [
                'dashboard' => [
                    'title' => 'Real-time Dashboard',
                    'url' => '/sensory_data/dashboard.php',
                    'roles' => ['admin']
                ],
                'weights' => [
                    'title' => 'Weight Monitoring',
                    'url' => '/sensory_data/weights.php',
                    'roles' => ['admin']
                ],
                'production' => [
                    'title' => 'Production Cycle',
                    'url' => '/sensory_data/production_cycle.php',
                    'roles' => ['admin']
                ],
                'temperatures' => [
                    'title' => 'Motor Temperatures',
                    'url' => '/sensory_data/motor_temperatures.php',
                    'roles' => ['admin']
                ]
            ]
        ],
        
        'admin' => [
            'title' => 'Administration',
            'icon' => 'fas fa-cog',
            'roles' => ['admin'],
            'submenu' => [
                'users' => [
                    'title' => 'User Management',
                    'url' => '/admin/users.php',
                    'roles' => ['admin']
                ],
                'parameters' => [
                    'title' => 'Product Parameters',
                    'url' => '/admin/product_parameters.php',
                    'roles' => ['admin']
                ],
                'notifications' => [
                    'title' => 'Notifications',
                    'url' => '/admin/notifications.php',
                    'roles' => ['admin']
                ],
                'password_management' => [
                    'title' => 'Password Management',
                    'url' => '/admin/password_reset_management.php',
                    'roles' => ['admin']
                ]
            ]
        ]
    ],
    
    'quick_actions' => [
        'admin' => [
            [
                'title' => 'Create Notification',
                'url' => '/admin/notifications.php',
                'icon' => 'fas fa-bell',
                'class' => 'btn-primary'
            ],
            [
                'title' => 'User Management',
                'url' => '/admin/users.php',
                'icon' => 'fas fa-users',
                'class' => 'btn-info'
            ],
            [
                'title' => 'System Analytics',
                'url' => '/admin/analytics.php',
                'icon' => 'fas fa-chart-line',
                'class' => 'btn-success'
            ]
        ],
        'supervisor' => [
            [
                'title' => 'Pending Approvals',
                'url' => '/dms/approval.php',
                'icon' => 'fas fa-check-circle',
                'class' => 'btn-warning'
            ],
            [
                'title' => 'Analytics Dashboard',
                'url' => '/dms/analytics.php',
                'icon' => 'fas fa-chart-bar',
                'class' => 'btn-info'
            ]
        ],
        'personnel' => [
            [
                'title' => 'New DMS Entry',
                'url' => '/dms/index.php',
                'icon' => 'fas fa-plus',
                'class' => 'btn-primary'
            ],
            [
                'title' => 'New Parameter Entry',
                'url' => '/parameters/index.php',
                'icon' => 'fas fa-plus-circle',
                'class' => 'btn-success'
            ]
        ],
        'Quality Control Inspection' => [
            [
                'title' => 'View DMS Records',
                'url' => '/dms/submission.php',
                'icon' => 'fas fa-list',
                'class' => 'btn-info'
            ],
            [
                'title' => 'Parameter Records',
                'url' => '/parameters/submission.php',
                'icon' => 'fas fa-table',
                'class' => 'btn-secondary'
            ]
        ]
    ]
];

/**
 * Check if user has access to a specific module
 */
function hasModuleAccess($module, $user_role) {
    global $role_permissions;
    return isset($role_permissions[$user_role][$module]) && !empty($role_permissions[$user_role][$module]);
}

/**
 * Check if user has specific permission for a module
 */
function hasPermission($module, $permission, $user_role) {
    global $role_permissions;
    if (!isset($role_permissions[$user_role][$module])) {
        return false;
    }
    
    $permissions = $role_permissions[$user_role][$module];
    if ($permissions === true) {
        return true;
    }
    
    return is_array($permissions) && in_array($permission, $permissions);
}

/**
 * Get navigation items for user role
 */
function getNavigationForRole($user_role) {
    global $navigation_structure;
    $filtered_nav = ['modules' => []];
    
    foreach ($navigation_structure['modules'] as $key => $module) {
        if (in_array($user_role, $module['roles'])) {
            $filtered_module = $module;
            
            // Filter submenu items based on role
            if (isset($module['submenu'])) {
                $filtered_submenu = [];
                foreach ($module['submenu'] as $sub_key => $sub_item) {
                    if (in_array($user_role, $sub_item['roles'])) {
                        $filtered_submenu[$sub_key] = $sub_item;
                    }
                }
                $filtered_module['submenu'] = $filtered_submenu;
            }
            
            $filtered_nav['modules'][$key] = $filtered_module;
        }
    }
    
    return $filtered_nav;
}

/**
 * Get quick actions for user role
 */
function getQuickActionsForRole($user_role) {
    global $navigation_structure;
    return isset($navigation_structure['quick_actions'][$user_role]) 
        ? $navigation_structure['quick_actions'][$user_role] 
        : [];
}

/**
 * Get current page info for breadcrumb and active state
 */
function getCurrentPageInfo($current_path) {
    $path_parts = explode('/', trim($current_path, '/'));
    
    $page_info = [
        'module' => '',
        'page' => '',
        'breadcrumb' => ['Dashboard'],
        'title' => 'Dashboard'
    ];
    
    if (count($path_parts) >= 1 && $path_parts[0] !== 'index.php') {
        $module = $path_parts[0];
        $page_info['module'] = $module;
        
        switch ($module) {
            case 'dms':
                $page_info['breadcrumb'][] = 'DMS';
                break;
            case 'parameters':
                $page_info['breadcrumb'][] = 'Parameters';
                break;
            case 'production_report':
                $page_info['breadcrumb'][] = 'Production Reports';
                break;
            case 'admin':
                $page_info['breadcrumb'][] = 'Administration';
                break;
            case 'sensory_data':
                $page_info['breadcrumb'][] = 'Sensory Data';
                break;
        }
        
        if (count($path_parts) >= 2) {
            $page = str_replace('.php', '', $path_parts[1]);
            $page_info['page'] = $page;
            
            switch ($page) {
                case 'index':
                    $page_info['breadcrumb'][] = 'Data Entry';
                    break;
                case 'submission':
                    $page_info['breadcrumb'][] = 'Records';
                    break;
                case 'analytics':
                    $page_info['breadcrumb'][] = 'Analytics';
                    break;
                case 'approval':
                    $page_info['breadcrumb'][] = 'Approvals';
                    break;
                case 'users':
                    $page_info['breadcrumb'][] = 'User Management';
                    break;
                case 'notifications':
                    $page_info['breadcrumb'][] = 'Notifications';
                    break;
            }
        }
    }
    
    $page_info['title'] = end($page_info['breadcrumb']);
    return $page_info;
}

/**
 * Generate relative path based on current script location
 */
function getBasePath($current_file = null) {
    if ($current_file === null) {
        $current_file = $_SERVER['SCRIPT_FILENAME'];
    }
    
    $script_path = $_SERVER['SCRIPT_NAME'];
    $path_parts = explode('/', trim($script_path, '/'));
    
    // Remove the script filename to get directory path
    array_pop($path_parts);
    
    // Calculate how many levels deep we are from document root
    $depth = count($path_parts) - 1; // Subtract 1 for the root level
    
    return str_repeat('../', $depth);
}
