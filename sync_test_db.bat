@echo off
echo ================================
echo   DATABASE SYNC UTILITY
echo   Sync erp_rpl to erp_rpl_test
echo ================================
echo.

REM Database configuration
set DB_HOST=127.0.0.1
set DB_USER=root
set DB_PASS=root
set SOURCE_DB=erp_rpl
set TARGET_DB=erp_rpl_test

echo [1/4] Creating backup of source database...
mysqldump -h %DB_HOST% -u %DB_USER% -p%DB_PASS% %SOURCE_DB% > temp_backup.sql
if errorlevel 1 (
    echo ❌ Failed to create backup!
    pause
    exit /b 1
)
echo ✅ Backup created successfully

echo.
echo [2/4] Dropping existing test database...
mysql -h %DB_HOST% -u %DB_USER% -p%DB_PASS% -e "DROP DATABASE IF EXISTS %TARGET_DB%;"
echo ✅ Test database dropped

echo.
echo [3/4] Creating fresh test database...
mysql -h %DB_HOST% -u %DB_USER% -p%DB_PASS% -e "CREATE DATABASE %TARGET_DB%;"
echo ✅ Test database created

echo.
echo [4/4] Importing data to test database...
mysql -h %DB_HOST% -u %DB_USER% -p%DB_PASS% %TARGET_DB% < temp_backup.sql
if errorlevel 1 (
    echo ❌ Failed to import data!
    pause
    exit /b 1
)
echo ✅ Data imported successfully

echo.
echo [CLEANUP] Removing temporary backup file...
del temp_backup.sql
echo ✅ Cleanup completed

echo.
echo ================================
echo   🎉 SYNC COMPLETED SUCCESSFULLY
echo   Database %TARGET_DB% is ready!
echo ================================
echo.
pause
