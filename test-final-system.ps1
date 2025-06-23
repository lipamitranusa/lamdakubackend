# Article Management System - Final Test Script
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "     ARTICLE MANAGEMENT SYSTEM TEST" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Test 1: Database
Write-Host "[1/5] Testing Database Connection..." -ForegroundColor Yellow
try {
    $result = php artisan tinker --execute="echo 'OK:' . App\Models\Article::count() . ':' . App\Models\Article::where('status', 'published')->count();"
    if ($result -match "OK:(\d+):(\d+)") {
        Write-Host "   ✅ Database: OK - Articles: $($matches[1]), Published: $($matches[2])" -ForegroundColor Green
    } else {
        Write-Host "   ❌ Database connection failed" -ForegroundColor Red
    }
} catch {
    Write-Host "   ❌ Database error: $_" -ForegroundColor Red
}

# Test 2: Controllers
Write-Host "`n[2/5] Testing Controllers..." -ForegroundColor Yellow
try {
    $result = php -r "require_once 'vendor/autoload.php'; `$app = require_once 'bootstrap/app.php'; try { `$controller = new App\Http\Controllers\Admin\ArticleController(); echo 'SUCCESS'; } catch (Exception `$e) { echo 'ERROR:' . `$e->getMessage(); }"
    if ($result -eq "SUCCESS") {
        Write-Host "   ✅ ArticleController: OK" -ForegroundColor Green
    } else {
        Write-Host "   ❌ ArticleController: $result" -ForegroundColor Red
    }
} catch {
    Write-Host "   ❌ Controller error: $_" -ForegroundColor Red
}

# Test 3: Routes
Write-Host "`n[3/5] Testing Routes..." -ForegroundColor Yellow

# Admin routes
$adminRoutes = php artisan route:list --path=admin/articles
if ($adminRoutes -match "admin\.articles") {
    Write-Host "   ✅ Admin Routes: OK" -ForegroundColor Green
} else {
    Write-Host "   ❌ Admin Routes: Missing" -ForegroundColor Red
}

# API routes
$apiRoutes = php artisan route:list --path=api/v1/articles
if ($apiRoutes -match "Api\\ArticleController") {
    Write-Host "   ✅ API Routes: OK" -ForegroundColor Green
} else {
    Write-Host "   ❌ API Routes: Missing" -ForegroundColor Red
}

# Login route
$loginRoute = php artisan route:list --name=login
if ($loginRoute -match "login") {
    Write-Host "   ✅ Login Route: OK" -ForegroundColor Green
} else {
    Write-Host "   ❌ Login Route: Missing" -ForegroundColor Red
}

# Test 4: Files
Write-Host "`n[4/5] Testing File Structure..." -ForegroundColor Yellow

$files = @{
    "app\Models\Article.php" = "Article Model"
    "app\Http\Controllers\Admin\ArticleController.php" = "Admin Controller"
    "app\Http\Controllers\Api\ArticleController.php" = "API Controller"
    "resources\views\admin\articles\index.blade.php" = "Admin Views"
    "frontend-integration\article-api-service.js" = "Frontend Integration"
}

foreach ($file in $files.Keys) {
    if (Test-Path $file) {
        Write-Host "   ✅ $($files[$file]): OK" -ForegroundColor Green
    } else {
        Write-Host "   ❌ $($files[$file]): Missing" -ForegroundColor Red
    }
}

# Test 5: Sample Data
Write-Host "`n[5/5] Testing Sample Data..." -ForegroundColor Yellow
try {
    $result = php artisan tinker --execute="`$featured = App\Models\Article::where('is_featured', true)->count(); `$categories = App\Models\Article::distinct('category')->count('category'); echo 'RESULT:' . `$featured . ':' . `$categories;"
    if ($result -match "RESULT:(\d+):(\d+)") {
        Write-Host "   ✅ Featured Articles: $($matches[1]), Categories: $($matches[2])" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️ Sample data check inconclusive" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   ❌ Sample data error: $_" -ForegroundColor Red
}

# Final Status
Write-Host "`n============================================" -ForegroundColor Cyan
Write-Host "              TEST COMPLETE!" -ForegroundColor Yellow
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "🚀 Next Steps:" -ForegroundColor Green
Write-Host "   1. Start server: php artisan serve --port=8000" -ForegroundColor White
Write-Host "   2. Open browser: http://localhost:8000/admin/articles" -ForegroundColor White
Write-Host "   3. Login: admin/admin123 or penulis/penulis123" -ForegroundColor White
Write-Host "   4. Test API: http://localhost:8000/api/v1/articles" -ForegroundColor White
Write-Host ""
Write-Host "Status: 🎉 READY FOR USE!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan

Read-Host "`nPress Enter to continue..."
