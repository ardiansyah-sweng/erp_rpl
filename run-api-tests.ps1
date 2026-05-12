# API Testing Script untuk Branch API (PowerShell)
# Jalankan script ini untuk test semua komponen API

Write-Host "🚀 Starting Branch API Tests..." -ForegroundColor Green
Write-Host "================================" -ForegroundColor Gray

# Test 1: Basic functionality
Write-Host "📋 Testing Basic API Functionality..." -ForegroundColor Yellow
$result1 = php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_get_list_of_branches
if ($LASTEXITCODE -eq 0) {
    Write-Host "✅ Basic test passed!" -ForegroundColor Green
} else {
    Write-Host "❌ Basic test failed!" -ForegroundColor Red
}

# Test 2: Create operation
Write-Host ""
Write-Host "🔧 Testing Create Operation..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_create_new_branch

# Test 3: Read operation
Write-Host ""
Write-Host "👁️ Testing Read Operation..." -ForegroundColor Yellow  
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_get_specific_branch

# Test 4: Update operation
Write-Host ""
Write-Host "✏️ Testing Update Operation..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_update_existing_branch

# Test 5: Delete operation
Write-Host ""
Write-Host "🗑️ Testing Delete Operation..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_delete_branch

# Test 6: Validation
Write-Host ""
Write-Host "✅ Testing Validation..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_cannot_create_branch_with_invalid_data

# Test 7: Error handling
Write-Host ""
Write-Host "⚠️ Testing Error Handling..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_returns_404_for_missing_branch

# Test 8: Search functionality
Write-Host ""
Write-Host "🔍 Testing Search Functionality..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_search_branches

# Test 9: Filter functionality  
Write-Host ""
Write-Host "🔽 Testing Filter Functionality..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_filter_by_status

# Test 10: Custom endpoints
Write-Host ""
Write-Host "🎯 Testing Custom Endpoints..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_active_branches_endpoint

# Test 11: Statistics
Write-Host ""
Write-Host "📊 Testing Statistics..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_branch_statistics

# Test 12: Response format
Write-Host ""
Write-Host "📋 Testing Response Format..." -ForegroundColor Yellow
php artisan test tests/Feature/BranchApiSimpleTest.php::test_consistent_response_format

# Summary
Write-Host ""
Write-Host "================================" -ForegroundColor Gray
Write-Host "🎉 All individual tests completed!" -ForegroundColor Green
Write-Host "🔍 Now running all tests together..." -ForegroundColor Yellow

# Run all tests together
php artisan test tests/Feature/BranchApiSimpleTest.php

Write-Host ""
Write-Host "✨ Testing complete! Check results above." -ForegroundColor Cyan
