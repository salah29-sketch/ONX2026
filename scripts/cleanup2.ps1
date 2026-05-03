# cleanup2.ps1 - Round 2 Cleanup
# Run from project root:
#   Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
#   .\scripts\cleanup2.ps1

Set-Location (Join-Path $PSScriptRoot '..')

function Remove-IfExists($path, $label) {
    if (Test-Path $path) {
        Remove-Item -Recurse -Force $path
        Write-Host "  [DELETED] $label" -ForegroundColor Green
    } else {
        Write-Host "  [SKIP]    $label (not found)" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "======================================" -ForegroundColor Cyan
Write-Host " Cleanup Round 2" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan

# --- [1] Delete unused views ---
Write-Host ""
Write-Host "[1/2] Removing unused Views..." -ForegroundColor Magenta

# views/home.blade.php - uses deleted routes (admin.settings.page, admin/settings/home)
Remove-IfExists "resources\views\home.blade.php" "views/home.blade.php (uses deleted routes)"

Write-Host ""
Write-Host "[2/2] Copying fixed layouts/admin.blade.php..." -ForegroundColor Magenta

if (Test-Path "admin.blade.php") {
    Copy-Item "admin.blade.php" "resources\views\layouts\admin.blade.php" -Force
    Write-Host "  [DONE] resources\views\layouts\admin.blade.php" -ForegroundColor Green
} else {
    Write-Host "  [WARN] admin.blade.php not found - copy manually" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "======================================" -ForegroundColor Cyan
Write-Host " Done!" -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""
