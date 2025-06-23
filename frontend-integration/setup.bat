@echo off
echo Starting Article Management Integration Setup...
echo.

REM Configuration
set FRONTEND_PATH=D:\laragon\www\LAMDAKU\accreditation-company-profile
set BACKEND_PATH=d:\laragon\www\LAMDAKU\lamdaku-cms-backend\frontend-integration

echo Checking frontend project...
if not exist "%FRONTEND_PATH%" (
    echo ERROR: Frontend project not found at %FRONTEND_PATH%
    echo Please update FRONTEND_PATH in this script.
    pause
    exit /b 1
)

if not exist "%FRONTEND_PATH%\package.json" (
    echo ERROR: package.json not found. This doesn't appear to be a React project.
    pause
    exit /b 1
)

echo ✓ Frontend project found
echo.

echo Creating directories...
mkdir "%FRONTEND_PATH%\src\services" 2>nul
mkdir "%FRONTEND_PATH%\src\components\Articles" 2>nul
mkdir "%FRONTEND_PATH%\src\hooks" 2>nul
mkdir "%FRONTEND_PATH%\src\utils" 2>nul
mkdir "%FRONTEND_PATH%\src\config" 2>nul
mkdir "%FRONTEND_PATH%\src\pages" 2>nul

echo ✓ Directories created
echo.

echo Copying integration files...
copy "%BACKEND_PATH%\article-api-service.js" "%FRONTEND_PATH%\src\services\" >nul
copy "%BACKEND_PATH%\ArticleComponents.jsx" "%FRONTEND_PATH%\src\components\Articles\" >nul
copy "%BACKEND_PATH%\hooks\useArticles.js" "%FRONTEND_PATH%\src\hooks\" >nul
copy "%BACKEND_PATH%\utils\articleHelpers.js" "%FRONTEND_PATH%\src\utils\" >nul
copy "%BACKEND_PATH%\config\api.js" "%FRONTEND_PATH%\src\config\" >nul

if errorlevel 1 (
    echo ✗ Some files failed to copy
) else (
    echo ✓ All files copied successfully
)

echo.
echo Creating .env file...
(
echo # Article Management API Configuration
echo REACT_APP_API_BASE_URL=http://localhost:8000/api/v1
echo REACT_APP_BACKEND_URL=http://localhost:8000
echo.
echo # Feature Flags
echo REACT_APP_ENABLE_COMMENTS=false
echo REACT_APP_ENABLE_SHARING=true
echo REACT_APP_ENABLE_FAVORITES=true
echo REACT_APP_ENABLE_READING_TIME=true
echo REACT_APP_ENABLE_VIEW_COUNT=true
echo REACT_APP_ENABLE_RELATED=true
echo REACT_APP_ENABLE_SEARCH_SUGGESTIONS=true
) > "%FRONTEND_PATH%\.env"

echo ✓ Environment file created
echo.

echo Setup complete!
echo.
echo Next steps:
echo 1. cd %FRONTEND_PATH%
echo 2. npm install axios react-router-dom
echo 3. Start your React development server
echo 4. Use the integration components in your app
echo.
echo Files copied to:
echo - src/services/article-api-service.js
echo - src/components/Articles/ArticleComponents.jsx  
echo - src/hooks/useArticles.js
echo - src/utils/articleHelpers.js
echo - src/config/api.js
echo.
pause
