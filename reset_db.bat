@echo off
echo ===================================
echo  Database Reset Script
echo ===================================

echo.
echo [1/4] Dropping all tables...
php artisan db:wipe --force

if %ERRORLEVEL% NEQ 0 (
    echo Error: Failed to drop database tables.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [2/4] Running migrations...
php artisan migrate --force

if %ERRORLEVEL% NEQ 0 (
    echo Error: Failed to run migrations.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [3/4] Seeding database...
php artisan db:seed --force

if %ERRORLEVEL% NEQ 0 (
    echo Error: Failed to seed database.
    pause
    exit /b %ERRORLEVEL%
)

echo.
echo [4/4] Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo.
echo ===================================
echo  Database reset completed successfully!
echo ===================================

timeout /t 3
exit /b 0
