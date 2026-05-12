<?php

// Quick Dusk Test Verification Script
// Run: php verify_dusk.php

require_once 'vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;

echo "🔍 Testing Dusk Configuration...\n";

try {
    // Start ChromeDriver
    echo "1. Starting ChromeDriver...\n";
    
    $options = (new ChromeOptions)->addArguments([
        '--window-size=1920,1080',
        '--disable-gpu',
        '--headless=new',
        '--no-sandbox'
    ]);
    
    $capabilities = DesiredCapabilities::chrome();
    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
    
    $driver = RemoteWebDriver::create('http://localhost:9515', $capabilities);
    echo "✅ ChromeDriver connected successfully!\n";
    
    // Test page access
    echo "2. Testing page access...\n";
    $driver->get('http://127.0.0.1:8000/branches/create');
    
    $title = $driver->getTitle();
    echo "✅ Page loaded: $title\n";
    
    // Test form elements
    echo "3. Testing form elements...\n";
    
    $branchName = $driver->findElement(WebDriverBy::name('branch_name'));
    $branchAddress = $driver->findElement(WebDriverBy::name('branch_address'));
    $branchPhone = $driver->findElement(WebDriverBy::name('branch_telephone'));
    $submitButton = $driver->findElement(WebDriverBy::xpath('//button[@type="submit"]'));
    
    echo "✅ All form elements found!\n";
    
    // Test form interaction
    echo "4. Testing form interaction...\n";
    $branchName->sendKeys('Dusk Verification Test');
    $branchAddress->sendKeys('Jl. Verification No. 123');
    $branchPhone->sendKeys('081234567890');
    
    echo "✅ Form interaction successful!\n";
    
    $driver->quit();
    echo "🎉 Dusk test verification PASSED!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "🔧 Try: php artisan dusk:chrome-driver --detect\n";
    if (isset($driver)) {
        $driver->quit();
    }
}
