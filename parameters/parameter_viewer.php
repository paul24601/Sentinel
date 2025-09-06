<?php
// Enhanced parameter viewing functions
class ParameterViewer {
    
    // Define parameter categories and their display information
    private static $parameterCategories = [
        'machine_info' => [
            'title' => 'Machine Information',
            'icon' => 'fas fa-cogs',
            'color' => 'primary',
            'fields' => [
                'MachineName' => ['label' => 'Machine Name', 'type' => 'text'],
                'RunNumber' => ['label' => 'Run Number', 'type' => 'text'],
                'Category' => ['label' => 'Category', 'type' => 'text'],
                'IRN' => ['label' => 'IRN', 'type' => 'text'],
                'Date' => ['label' => 'Date', 'type' => 'date'],
                'Time' => ['label' => 'Time', 'type' => 'time'],
                'startTime' => ['label' => 'Start Time', 'type' => 'time'],
                'endTime' => ['label' => 'End Time', 'type' => 'time']
            ]
        ],
        'product_details' => [
            'title' => 'Product Details',
            'icon' => 'fas fa-cube',
            'color' => 'info',
            'fields' => [
                'ProductName' => ['label' => 'Product Name', 'type' => 'text'],
                'ProductNumber' => ['label' => 'Product Number', 'type' => 'text'],
                'Color' => ['label' => 'Color', 'type' => 'text'],
                'MoldName' => ['label' => 'Mold Name', 'type' => 'text'],
                'CavityActive' => ['label' => 'Active Cavities', 'type' => 'number'],
                'GrossWeight' => ['label' => 'Gross Weight', 'type' => 'decimal', 'unit' => 'g'],
                'NetWeight' => ['label' => 'Net Weight', 'type' => 'decimal', 'unit' => 'g'],
                'DryingTime' => ['label' => 'Drying Time', 'type' => 'decimal', 'unit' => 'hours'],
                'DryingTemperature' => ['label' => 'Drying Temperature', 'type' => 'decimal', 'unit' => '°C']
            ]
        ],
        'material_composition' => [
            'title' => 'Material Composition',
            'icon' => 'fas fa-flask',
            'color' => 'secondary',
            'fields' => [
                'MaterialName' => ['label' => 'Material Name', 'type' => 'text'],
                'Percentage' => ['label' => 'Percentage', 'type' => 'decimal', 'unit' => '%'],
                'Supplier' => ['label' => 'Supplier', 'type' => 'text'],
                'Grade' => ['label' => 'Grade', 'type' => 'text'],
                'LotNumber' => ['label' => 'Lot Number', 'type' => 'text'],
                'Properties' => ['label' => 'Properties', 'type' => 'text']
            ]
        ],
        'temperatures' => [
            'title' => 'Temperature Settings',
            'icon' => 'fas fa-thermometer-half',
            'color' => 'warning',
            'fields' => [
                'Zone1' => ['label' => 'Zone 1', 'type' => 'decimal', 'unit' => '°C'],
                'Zone2' => ['label' => 'Zone 2', 'type' => 'decimal', 'unit' => '°C'],
                'Zone3' => ['label' => 'Zone 3', 'type' => 'decimal', 'unit' => '°C'],
                'Zone4' => ['label' => 'Zone 4', 'type' => 'decimal', 'unit' => '°C'],
                'Zone5' => ['label' => 'Zone 5', 'type' => 'decimal', 'unit' => '°C'],
                'Zone6' => ['label' => 'Zone 6', 'type' => 'decimal', 'unit' => '°C'],
                'Zone7' => ['label' => 'Zone 7', 'type' => 'decimal', 'unit' => '°C'],
                'Zone8' => ['label' => 'Zone 8', 'type' => 'decimal', 'unit' => '°C']
            ]
        ],
        'process_parameters' => [
            'title' => 'Process Parameters',
            'icon' => 'fas fa-sliders-h',
            'color' => 'success',
            'fields' => [
                'InjectionPressure' => ['label' => 'Injection Pressure', 'type' => 'decimal', 'unit' => 'bar'],
                'InjectionSpeed' => ['label' => 'Injection Speed', 'type' => 'decimal', 'unit' => 'mm/s'],
                'HoldPressure' => ['label' => 'Hold Pressure', 'type' => 'decimal', 'unit' => 'bar'],
                'HoldTime' => ['label' => 'Hold Time', 'type' => 'decimal', 'unit' => 's'],
                'CoolingTime' => ['label' => 'Cooling Time', 'type' => 'decimal', 'unit' => 's'],
                'CycleTime' => ['label' => 'Cycle Time', 'type' => 'decimal', 'unit' => 's']
            ]
        ]
    ];
    
    // Format value based on type
    public static function formatValue($value, $type, $unit = null) {
        if ($value === null || $value === '') {
            return '<span class="text-muted fst-italic">Not set</span>';
        }
        
        switch ($type) {
            case 'decimal':
                $formatted = is_numeric($value) ? number_format((float)$value, 2) : $value;
                return $formatted . ($unit ? ' ' . $unit : '');
                
            case 'number':
                return is_numeric($value) ? number_format((int)$value) : $value;
                
            case 'date':
                return date('M d, Y', strtotime($value));
                
            case 'time':
                return date('H:i:s', strtotime($value));
                
            case 'text':
            default:
                return htmlspecialchars($value);
        }
    }
    
    // Generate enhanced parameter display
    public static function renderParameterSection($title, $icon, $color, $fields, $data) {
        $hasData = false;
        $fieldsWithData = 0;
        $totalFields = count($fields);
        
        // Check if section has any data
        foreach ($fields as $key => $config) {
            if (isset($data[$key]) && $data[$key] !== null && $data[$key] !== '') {
                $hasData = true;
                $fieldsWithData++;
            }
        }
        
        ob_start();
        ?>
        <div class="card mb-4">
            <div class="card-header bg-<?= $color ?> text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="<?= $icon ?> me-2"></i><?= $title ?>
                        <span class="badge bg-light text-dark ms-2"><?= $fieldsWithData ?>/<?= $totalFields ?> fields</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if ($hasData || true): // Always show section, even if empty ?>
                    <div class="row g-3">
                        <?php foreach ($fields as $key => $config): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="border rounded p-3 h-100 <?= isset($data[$key]) && $data[$key] !== null && $data[$key] !== '' ? 'bg-light' : 'bg-white border-dashed' ?>">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <strong class="text-<?= $color ?>"><?= $config['label'] ?></strong>
                                        <?php if (isset($data[$key]) && $data[$key] !== null && $data[$key] !== ''): ?>
                                            <i class="fas fa-check-circle text-success"></i>
                                        <?php else: ?>
                                            <i class="fas fa-circle text-muted opacity-50"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="parameter-value">
                                        <?= self::formatValue($data[$key] ?? null, $config['type'], $config['unit'] ?? null) ?>
                                    </div>
                                    <?php if (isset($config['unit']) && isset($data[$key]) && $data[$key] !== null && $data[$key] !== ''): ?>
                                        <small class="text-muted">Unit: <?= $config['unit'] ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="<?= $icon ?> fa-3x mb-3 opacity-25"></i>
                        <p>No data available for this section</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
        .border-dashed {
            border-style: dashed !important;
            border-width: 2px !important;
        }
        .parameter-value {
            font-size: 1.1em;
            font-weight: 500;
            min-height: 1.5em;
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
?>
