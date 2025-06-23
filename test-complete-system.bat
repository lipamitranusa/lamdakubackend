@echo off
echo ================================
echo Article Management System Test
echo ================================
echo.

echo 1. Testing Controller Fix...
php -r "require_once 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; try { $controller = new App\Http\Controllers\Admin\ArticleController(); echo 'SUCCESS: ArticleController works!' . PHP_EOL; } catch (Exception $e) { echo 'ERROR: ' . $e->getMessage() . PHP_EOL; }"

echo.
echo 2. Testing Database Connection...
php artisan tinker --execute="echo 'Articles in database: ' . App\Models\Article::count() . PHP_EOL;"

echo.
echo 3. Testing Route Registration...
php artisan route:list --path=admin/articles | findstr "articles"

echo.
echo 4. Testing API Routes...
php artisan route:list --path=api/v1/articles | findstr "articles"

echo.
echo ================================
echo Test Complete!
echo ================================
echo.
echo Next Steps:
echo 1. Start server: php artisan serve
echo 2. Access admin: http://localhost:8000/admin/articles
echo 3. Login with: admin/admin123
echo.
