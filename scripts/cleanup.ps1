# cleanup.ps1 - Laravel Project Cleanup Script
# Run from project root:
#   Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
#   .\scripts\cleanup.ps1

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
Write-Host " Laravel Cleanup Script" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan

# --- [1] Controllers ---
Write-Host ""
Write-Host "[1/5] Removing unused Controllers..." -ForegroundColor Magenta
Remove-IfExists "app\Http\Controllers\Admin\AppointmentsController.php"   "AppointmentsController.php"
Remove-IfExists "app\Http\Controllers\Admin\SystemCalendarController.php" "SystemCalendarController.php"
Remove-IfExists "app\Http\Controllers\Admin\AdminController.php"          "AdminController.php"
Remove-IfExists "app\Http\Controllers\Admin\GalleryController.php"        "GalleryController.php"
Remove-IfExists "app\Http\Controllers\Api\V1\Admin\AppointmentsApiController.php" "AppointmentsApiController.php"

# --- [2] Form Requests ---
Write-Host ""
Write-Host "[2/5] Removing unused Form Requests..." -ForegroundColor Magenta
Remove-IfExists "app\Http\Requests\StoreAppointmentRequest.php"       "StoreAppointmentRequest.php"
Remove-IfExists "app\Http\Requests\UpdateAppointmentRequest.php"      "UpdateAppointmentRequest.php"
Remove-IfExists "app\Http\Requests\MassDestroyAppointmentRequest.php" "MassDestroyAppointmentRequest.php"

# --- [3] Resources and Models ---
Write-Host ""
Write-Host "[3/5] Removing unused Resources and Models..." -ForegroundColor Magenta
Remove-IfExists "app\Http\Resources\Admin\AppointmentResource.php" "AppointmentResource.php"
Remove-IfExists "app\Models\Appointment.php"                       "Appointment.php"

# --- [4] Views ---
Write-Host ""
Write-Host "[4/5] Removing unused Views..." -ForegroundColor Magenta
Remove-IfExists "resources\views\admin\appointments"    "views/admin/appointments/"
Remove-IfExists "resources\views\admin\gallery"         "views/admin/gallery/"
Remove-IfExists "resources\views\admin\system_calendar" "views/admin/system_calendar/"
Remove-IfExists "resources\views\portfolio.blade.php"   "views/portfolio.blade.php"

# --- [5] Copy clean files ---
Write-Host ""
Write-Host "[5/5] Copying clean files..." -ForegroundColor Magenta

if (Test-Path "web.php") {
    Copy-Item "web.php" "routes\web.php" -Force
    Write-Host "  [DONE] routes\web.php" -ForegroundColor Green
} else {
    Write-Host "  [WARN] web.php not found - copy manually to routes\" -ForegroundColor Yellow
}

if (Test-Path "menu.blade.php") {
    Copy-Item "menu.blade.php" "resources\views\partials\menu.blade.php" -Force
    Write-Host "  [DONE] resources\views\partials\menu.blade.php" -ForegroundColor Green
} else {
    Write-Host "  [WARN] menu.blade.php not found - copy manually" -ForegroundColor Yellow
}

if (Test-Path "Employee.php") {
    Copy-Item "Employee.php" "app\Models\Employee.php" -Force
    Write-Host "  [DONE] app\Models\Employee.php" -ForegroundColor Green
} else {
    Write-Host "  [WARN] Employee.php not found - copy manually" -ForegroundColor Yellow
}

if (Test-Path "Service.php") {
    Copy-Item "Service.php" "app\Models\Service.php" -Force
    Write-Host "  [DONE] app\Models\Service.php" -ForegroundColor Green
} else {
    Write-Host "  [WARN] Service.php not found - copy manually" -ForegroundColor Yellow
}

# --- Done ---
Write-Host ""
Write-Host "======================================" -ForegroundColor Cyan
Write-Host " Cleanup complete!" -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Run these commands next:" -ForegroundColor White
Write-Host "  php artisan config:clear" -ForegroundColor Gray
Write-Host "  php artisan route:clear" -ForegroundColor Gray
Write-Host "  php artisan view:clear" -ForegroundColor Gray
Write-Host "  php artisan optimize" -ForegroundColor Gray
Write-Host ""
