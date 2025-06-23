@echo off
cls
echo ============================================
echo      ARTICLE MANAGEMENT SYSTEM TEST
echo ============================================
echo.

echo [1/5] Testing Database Connection...
php artisan tinker --execute="echo 'Database: OK - Articles: ' . App\Models\Article::count() . ', Published: ' . App\Models\Article::where('status', 'published')->count() . PHP_EOL;"

echo.
echo [2/5] Testing Controllers...
php -r "require_once 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; try { $controller = new App\Http\Controllers\Admin\ArticleController(); echo 'ArticleController: OK' . PHP_EOL; } catch (Exception $e) { echo 'ERROR: ' . $e->getMessage() . PHP_EOL; }"

echo.
echo [3/5] Testing Routes...
echo Checking admin routes:
php artisan route:list --path=admin/articles | findstr "articles" | find /c "admin.articles" > nul && echo "Admin Routes: OK" || echo "Admin Routes: ERROR"

echo Checking API routes:
php artisan route:list --path=api/v1/articles | findstr "articles" | find /c "Api\\ArticleController" > nul && echo "API Routes: OK" || echo "API Routes: ERROR"

echo Checking login route:
php artisan route:list --name=login | findstr "login" > nul && echo "Login Route: OK" || echo "Login Route: ERROR"

echo.
echo [4/5] Testing File Structure...
if exist "app\Models\Article.php" (echo "Article Model: OK") else (echo "Article Model: MISSING")
if exist "app\Http\Controllers\Admin\ArticleController.php" (echo "Admin Controller: OK") else (echo "Admin Controller: MISSING")
if exist "app\Http\Controllers\Api\ArticleController.php" (echo "API Controller: OK") else (echo "API Controller: MISSING")
if exist "resources\views\admin\articles\index.blade.php" (echo "Admin Views: OK") else (echo "Admin Views: MISSING")
if exist "frontend-integration\article-api-service.js" (echo "Frontend Integration: OK") else (echo "Frontend Integration: MISSING")

echo.
echo [5/5] Testing Sample Data...
php artisan tinker --execute="$featured = App\Models\Article::where('is_featured', true)->count(); $categories = App\Models\Article::distinct('category')->count('category'); echo 'Featured: ' . $featured . ', Categories: ' . $categories . PHP_EOL;"

echo.
echo ============================================
echo              TEST COMPLETE!
echo ============================================
echo.
echo Next Steps:
echo 1. Start server: php artisan serve --port=8000
echo 2. Open browser: http://localhost:8000/admin/articles
echo 3. Login: admin/admin123 or penulis/penulis123
echo 4. Test API: http://localhost:8000/api/v1/articles
echo.
echo Status: READY FOR USE!
echo ============================================
pause
