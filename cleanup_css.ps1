# PowerShell script to remove complex CSS blocks from ALL PHP files

$files = @(
    "admin\notifications.php",
    "admin\password_reset_management.php",
    "admin\product_parameters.php",
    "admin\users.php",
    "dms\analytics.php",
    "dms\submission.php",
    "parameters\analytics.php",
    "parameters\submission.php"
)

foreach ($file in $files) {
    Write-Host "Processing $file..."
    
    if (Test-Path $file) {
        # Read the file content
        $content = Get-Content $file -Raw
        
        # Remove CSS blocks between <style> and </style>
        $content = $content -replace '(?s)<style>.*?</style>', ''
        
        # Clean up multiple empty lines
        $content = $content -replace '(?m)^\s*$\r?\n', "`n"
        $content = $content -replace '\n{3,}', "`n`n"
        
        # Write back to file
        Set-Content -Path $file -Value $content -NoNewline
        
        Write-Host "Cleaned $file"
    } else {
        Write-Host "File not found: $file"
    }
}

Write-Host "CSS cleanup complete!"
