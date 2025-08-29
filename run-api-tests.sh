#!/bin/bash

# API Testing Script untuk Branch API
# Jalankan script ini untuk test semua komponen API

echo "🚀 Starting Branch API Tests..."
echo "================================"

# Test 1: Basic functionality
echo "📋 Testing Basic API Functionality..."
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_get_list_of_branches

if [ $? -eq 0 ]; then
    echo "✅ Basic test passed!"
else
    echo "❌ Basic test failed!"
    exit 1
fi

# Test 2: CRUD operations
echo ""
echo "🔧 Testing CRUD Operations..."
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_create_new_branch
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_get_specific_branch
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_update_existing_branch
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_delete_branch

# Test 3: Validation
echo ""
echo "✅ Testing Validation..."
php artisan test tests/Feature/BranchApiSimpleTest.php::test_cannot_create_branch_with_invalid_data
php artisan test tests/Feature/BranchApiSimpleTest.php::test_returns_404_for_missing_branch

# Test 4: Advanced features
echo ""
echo "🔍 Testing Advanced Features..."
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_search_branches
php artisan test tests/Feature/BranchApiSimpleTest.php::test_can_filter_by_status
php artisan test tests/Feature/BranchApiSimpleTest.php::test_active_branches_endpoint

# Summary
echo ""
echo "================================"
echo "🎉 All tests completed!"
echo "Check results above for any failures."
