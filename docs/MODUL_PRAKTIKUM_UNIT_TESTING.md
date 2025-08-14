# PRAKTIKUM 01: PURE UNIT TESTING DALAM LARAVEL
## Testing Komponen Terisolasi dengan Mocking dan Stubbing

### **Informasi Modul**
- **Mata Kuliah**: Rekayasa Perangkat Lunak / Software Testing
- **Topik**: Pure Unit Testing - Isolated Component Testing
- **Framework**: PHPUnit dengan Laravel Support
- **Durasi**: 90 menit (1.5 jam)
- **Level**: Intermediate to Advanced

> **📋 NOTE**: Untuk Model Integration Testing (dengan database), lihat file terpisah: **[MODUL_PRAKTIKUM_INTEGRATION_TESTING.md](./MODUL_PRAKTIKUM_INTEGRATION_TESTING.md)**

---

## **TUJUAN PEMBELAJARAN**

Setelah menyelesaikan praktikum pure unit testing ini, mahasiswa diharapkan mampu:

1. **Memahami konsep Pure Unit Testing** yang truly isolated dari external dependencies
2. **Mengimplementasikan Mocking dan Stubbing** untuk mengisolasi komponen yang ditest
3. **Membedakan Pure Unit Testing dari Integration Testing** dalam konteks praktis
4. **Menguji business logic** tanpa bergantung pada database, file system, atau network
5. **Menggunakan Test Doubles** (Mocks, Stubs, Fakes) dengan efektif
6. **Menganalisis kapan pure unit testing appropriate** dan memberikan value

---

## **DASAR TEORI PURE UNIT TESTING**

### **1. Apa itu Pure Unit Testing?**

Pure Unit Testing adalah pengujian komponen software dalam **complete isolation** dari dependencies:

- **No Database**: Tidak menggunakan database sama sekali
- **No File System**: Tidak mengakses file atau storage
- **No Network**: Tidak melakukan HTTP calls atau API requests
- **No Framework**: Minimal dependency pada Laravel framework
- **Fast Execution**: Setiap test berjalan dalam milliseconds

### **2. Characteristics of Pure Unit Testing**

```php
// ✅ Pure Unit Test - Isolated, Fast, Repeatable
class TaxCalculatorTest extends TestCase 
{
    public function test_calculate_vat_percentage()
    {
        $calculator = new TaxCalculator();
        $result = $calculator->calculateVAT(1000, 10);
        $this->assertEquals(100, $result);
        // No database, no external calls, pure logic
    }
}

// ❌ NOT Pure Unit Test - Has database dependency
class BranchTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_branch_creation()
    {
        $branch = Branch::create(['name' => 'Jakarta']);
        $this->assertDatabaseHas('branches', ['name' => 'Jakarta']);
        // This is integration testing, not unit testing
    }
}
```

### **3. Test Doubles: Mocks, Stubs, dan Fakes**

```php
// MOCK - Verifies behavior/interactions
$paymentGateway = $this->createMock(PaymentGatewayInterface::class);
$paymentGateway->expects($this->once())
               ->method('charge')
               ->with(1000)
               ->willReturn(true);

// STUB - Provides predefined responses
$exchangeRate = $this->createStub(ExchangeRateInterface::class);
$exchangeRate->method('getRate')
             ->willReturn(15000);

// FAKE - Working implementation for testing
$fakeEmailService = new FakeEmailService();
```

### **4. Kapan Menggunakan Pure Unit Testing?**

Pure unit testing ideal untuk komponen yang **can be isolated**:

| Component Type | Example | Alasan |
|----------------|---------|---------|
| **Mathematical Operations** | `calculateDiscount($price, $percent)` | Pure computation |
| **String Utilities** | `slugify($title)` | Input → Output transformation |
| **Business Rules** | `isEligibleForDiscount($user)` | Logic-only decisions |
| **Data Validators** | `validateEmail($email)` | No external dependencies |
| **Service Classes** | `OrderProcessor` | Can be isolated with mocks |

### **5. Industry Reality: Pure Unit Testing Usage**

```
Laravel Project Testing Reality:
tests/
├── Unit/               (5-15% of total tests)
│   ├── Services/       (dengan mocking)
│   ├── Helpers/        (utility functions)
│   └── Validators/     (business rules)
└── Feature/            (85-95% of total tests)
    ├── HTTP endpoints
    ├── Database operations
    └── Workflow integration
```

**Key Insight**: Pure unit testing valuable tapi **limited scope** dalam Laravel karena framework design.

---

## **ALUR PURE UNIT TESTING LENGKAP**

### **FASE 1: PERSIAPAN TESTING ENVIRONMENT** ⏱️ 15 menit

#### **Langkah 1.1: Setup Testing Environment untuk Pure Unit Testing**

1. **Konfigurasi phpunit.xml untuk Unit Testing**
   ```xml
   <testsuites>
       <testsuite name="Unit">
           <directory suffix="Test.php">./tests/Unit</directory>
       </testsuite>
       <testsuite name="Feature">
           <directory suffix="Test.php">./tests/Feature</directory>
       </testsuite>
   </testsuites>
   
   <!-- Environment untuk pure unit testing -->
   <env name="APP_ENV" value="testing"/>
   <env name="CACHE_DRIVER" value="array"/>
   <env name="SESSION_DRIVER" value="array"/>
   <env name="QUEUE_CONNECTION" value="sync"/>
   <!-- NO DATABASE untuk pure unit tests -->
   ```

2. **Struktur Direktori untuk Pure Unit Testing**
   ```
   tests/
   ├── Unit/
   │   ├── Services/
   │   │   ├── DiscountServiceTest.php
   │   │   ├── OrderProcessorTest.php
   │   │   └── PaymentServiceTest.php
   │   ├── Helpers/
   │   │   ├── CurrencyHelperTest.php
   │   │   ├── StringHelperTest.php
   │   │   └── ValidationHelperTest.php
   │   └── Validators/
   │       ├── EmailValidatorTest.php
   │       └── BusinessRuleValidatorTest.php
   └── Feature/
       └── (integration tests)
   ```

3. **Verifikasi No Database Dependency**
   ```bash
   # Run hanya unit tests - should be very fast
   php artisan test --testsuite=Unit
   
   # Should complete in < 1 second for pure unit tests
   time php artisan test --testsuite=Unit
   ```

**✅ Checkpoint 1**: Pure unit testing environment terkonfigurasi tanpa database dependencies

---

### **FASE 2: IMPLEMENTASI HELPER FUNCTION TESTING** ⏱️ 20 menit

#### **Langkah 2.1: Buat Helper Class untuk Pure Unit Testing**

Buat file `app/Helpers/CurrencyHelper.php`:

```php
<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Format amount to currency string
     */
    public static function format(float $amount, string $currency = 'IDR'): string
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException('Amount cannot be negative');
        }
        
        switch ($currency) {
            case 'IDR':
                return 'Rp ' . number_format($amount, 0, ',', '.');
            case 'USD':
                return '$' . number_format($amount, 2, '.', ',');
            default:
                throw new \InvalidArgumentException("Unsupported currency: {$currency}");
        }
    }
    
    /**
     * Calculate percentage of amount
     */
    public static function calculatePercentage(float $amount, float $percentage): float
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Percentage must be between 0 and 100');
        }
        
        return round($amount * ($percentage / 100), 2);
    }
    
    /**
     * Convert currency string to float
     */
    public static function parse(string $currencyString): float
    {
        $cleaned = preg_replace('/[^\d.,]/', '', $currencyString);
        $cleaned = str_replace(',', '', $cleaned);
        
        return (float) $cleaned;
    }
}
```

#### **Langkah 2.2: Pure Unit Tests untuk Helper Functions**

Buat file `tests/Unit/Helpers/CurrencyHelperTest.php`:

```php
<?php

namespace Tests\Unit\Helpers;

use App\Helpers\CurrencyHelper;
use PHPUnit\Framework\TestCase; // Note: Using PHPUnit TestCase, NOT Laravel's
use InvalidArgumentException;

/**
 * Pure Unit Tests untuk CurrencyHelper
 * 
 * Characteristics:
 * - No Laravel framework dependencies
 * - No database, no file system, no network
 * - Fast execution (< 1ms per test)
 * - Complete isolation
 */
class CurrencyHelperTest extends TestCase
{
    /**
     * Test: Format IDR currency correctly
     * @test
     */
    public function it_formats_idr_currency_correctly(): void
    {
        // Arrange
        $amount = 1500000;
        
        // Act
        $result = CurrencyHelper::format($amount, 'IDR');
        
        // Assert
        $this->assertEquals('Rp 1.500.000', $result);
    }
    
    /**
     * Test: Format USD currency correctly
     * @test
     */
    public function it_formats_usd_currency_correctly(): void
    {
        // Arrange
        $amount = 1234.56;
        
        // Act
        $result = CurrencyHelper::format($amount, 'USD');
        
        // Assert
        $this->assertEquals('$1,234.56', $result);
    }
    
    /**
     * Test: Default currency is IDR
     * @test
     */
    public function it_uses_idr_as_default_currency(): void
    {
        // Act
        $result = CurrencyHelper::format(100000);
        
        // Assert
        $this->assertEquals('Rp 100.000', $result);
    }
    
    /**
     * Test: Exception for negative amount
     * @test
     */
    public function it_throws_exception_for_negative_amount(): void
    {
        // Arrange & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount cannot be negative');
        
        // Act
        CurrencyHelper::format(-100);
    }
    
    /**
     * Test: Multiple scenarios with data provider
     * @test
     * @dataProvider currencyFormattingProvider
     */
    public function it_formats_various_amounts_correctly($amount, $currency, $expected): void
    {
        // Act
        $result = CurrencyHelper::format($amount, $currency);
        
        // Assert
        $this->assertEquals($expected, $result);
    }
    
    public function currencyFormattingProvider(): array
    {
        return [
            'small_idr' => [1000, 'IDR', 'Rp 1.000'],
            'large_idr' => [5000000, 'IDR', 'Rp 5.000.000'],
            'small_usd' => [10.50, 'USD', '$10.50'],
            'large_usd' => [50000.99, 'USD', '$50,000.99'],
            'zero_amount' => [0, 'IDR', 'Rp 0']
        ];
    }
    
    /**
     * Test: Calculate percentage correctly
     * @test
     * @dataProvider percentageProvider
     */
    public function it_calculates_percentage_correctly($amount, $percentage, $expected): void
    {
        // Act
        $result = CurrencyHelper::calculatePercentage($amount, $percentage);
        
        // Assert
        $this->assertEquals($expected, $result);
    }
    
    public function percentageProvider(): array
    {
        return [
            'ten_percent' => [1000, 10, 100.0],
            'fifty_percent' => [200, 50, 100.0],
            'zero_percent' => [1000, 0, 0.0],
            'decimal_percentage' => [1000, 12.5, 125.0]
        ];
    }
}
```

**✅ Checkpoint 2**: Helper function testing implemented dengan pure unit testing approach

---

### **FASE 3: BUSINESS LOGIC TESTING DENGAN MOCKING** ⏱️ 30 menit

#### **Langkah 3.1: Buat Service Class dengan Dependencies**

Buat file `app/Services/DiscountService.php`:

```php
<?php

namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use App\Contracts\CouponValidatorInterface;
use App\Helpers\CurrencyHelper;

class DiscountService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CouponValidatorInterface $couponValidator
    ) {}
    
    /**
     * Calculate discount for user order
     */
    public function calculateDiscount(int $userId, string $couponCode, float $orderAmount): array
    {
        // Get user data
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        // Validate coupon
        $coupon = $this->couponValidator->validate($couponCode);
        
        if (!$coupon['valid']) {
            return [
                'discount_amount' => 0,
                'final_amount' => $orderAmount,
                'coupon_applied' => false,
                'reason' => 'Invalid coupon'
            ];
        }
        
        // Check user eligibility
        if (!$this->isUserEligible($user, $coupon)) {
            return [
                'discount_amount' => 0,
                'final_amount' => $orderAmount,
                'coupon_applied' => false,
                'reason' => 'User not eligible'
            ];
        }
        
        // Calculate discount
        $discountAmount = $this->calculateDiscountAmount($orderAmount, $coupon);
        
        return [
            'discount_amount' => $discountAmount,
            'final_amount' => $orderAmount - $discountAmount,
            'coupon_applied' => true,
            'reason' => 'Discount applied successfully'
        ];
    }
    
    private function isUserEligible(array $user, array $coupon): bool
    {
        // VIP users always eligible
        if ($user['type'] === 'vip') {
            return true;
        }
        
        // Regular users need minimum order count
        return $user['order_count'] >= $coupon['min_orders'];
    }
    
    private function calculateDiscountAmount(float $orderAmount, array $coupon): float
    {
        if ($coupon['type'] === 'percentage') {
            $discount = CurrencyHelper::calculatePercentage($orderAmount, $coupon['value']);
            return min($discount, $coupon['max_discount'] ?? $discount);
        }
        
        if ($coupon['type'] === 'fixed') {
            return min($coupon['value'], $orderAmount);
        }
        
        return 0;
    }
}
```

#### **Langkah 3.2: Buat Interface Contracts**

Buat file `app/Contracts/UserRepositoryInterface.php`:

```php
<?php

namespace App\Contracts;

interface UserRepositoryInterface
{
    public function findById(int $id): ?array;
}
```

Buat file `app/Contracts/CouponValidatorInterface.php`:

```php
<?php

namespace App\Contracts;

interface CouponValidatorInterface
{
    public function validate(string $code): array;
}
```

#### **Langkah 3.3: Pure Unit Tests dengan Mocking**

Buat file `tests/Unit/Services/DiscountServiceTest.php`:

```php
<?php

namespace Tests\Unit\Services;

use App\Services\DiscountService;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\CouponValidatorInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Pure Unit Tests untuk DiscountService
 * 
 * Using mocks to completely isolate business logic
 * No database, no external dependencies
 */
class DiscountServiceTest extends TestCase
{
    private DiscountService $discountService;
    private MockObject $userRepository;
    private MockObject $couponValidator;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mocks for dependencies
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->couponValidator = $this->createMock(CouponValidatorInterface::class);
        
        // Inject mocks into service
        $this->discountService = new DiscountService(
            $this->userRepository,
            $this->couponValidator
        );
    }
    
    /**
     * Test: VIP user gets percentage discount
     * @test
     */
    public function it_calculates_percentage_discount_for_vip_user(): void
    {
        // Arrange - Setup mock behaviors
        $this->userRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn([
                'id' => 1,
                'type' => 'vip',
                'order_count' => 5
            ]);
            
        $this->couponValidator
            ->expects($this->once())
            ->method('validate')
            ->with('VIP20')
            ->willReturn([
                'valid' => true,
                'type' => 'percentage',
                'value' => 20,
                'max_discount' => 100,
                'min_orders' => 10
            ]);
        
        // Act
        $result = $this->discountService->calculateDiscount(1, 'VIP20', 500);
        
        // Assert
        $this->assertEquals(100, $result['discount_amount']); // 20% of 500 = 100
        $this->assertEquals(400, $result['final_amount']);
        $this->assertTrue($result['coupon_applied']);
    }
    
    /**
     * Test: Invalid coupon returns zero discount
     * @test
     */
    public function it_returns_zero_discount_for_invalid_coupon(): void
    {
        // Arrange
        $this->userRepository
            ->method('findById')
            ->willReturn(['id' => 1, 'type' => 'regular', 'order_count' => 5]);
            
        $this->couponValidator
            ->method('validate')
            ->willReturn(['valid' => false]);
        
        // Act
        $result = $this->discountService->calculateDiscount(1, 'INVALID', 100);
        
        // Assert
        $this->assertEquals(0, $result['discount_amount']);
        $this->assertEquals(100, $result['final_amount']);
        $this->assertFalse($result['coupon_applied']);
        $this->assertEquals('Invalid coupon', $result['reason']);
    }
    
    /**
     * Test: Exception for non-existent user
     * @test
     */
    public function it_throws_exception_for_non_existent_user(): void
    {
        // Arrange
        $this->userRepository
            ->method('findById')
            ->willReturn(null);
        
        // Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User not found');
        
        // Act
        $this->discountService->calculateDiscount(999, 'COUPON', 100);
    }
    
    /**
     * Test: Regular user ineligible for coupon
     * @test
     */
    public function it_denies_discount_for_ineligible_regular_user(): void
    {
        // Arrange
        $this->userRepository
            ->method('findById')
            ->willReturn([
                'id' => 2,
                'type' => 'regular',
                'order_count' => 3 // Less than required
            ]);
            
        $this->couponValidator
            ->method('validate')
            ->willReturn([
                'valid' => true,
                'type' => 'percentage',
                'value' => 15,
                'min_orders' => 5 // User has only 3 orders
            ]);
        
        // Act
        $result = $this->discountService->calculateDiscount(2, 'REGULAR15', 100);
        
        // Assert
        $this->assertEquals(0, $result['discount_amount']);
        $this->assertEquals('User not eligible', $result['reason']);
    }
    
    /**
     * Test: Fixed discount with order amount limit
     * @test
     */
    public function it_applies_fixed_discount_with_order_limit(): void
    {
        // Arrange
        $this->userRepository
            ->method('findById')
            ->willReturn(['id' => 1, 'type' => 'vip', 'order_count' => 10]);
            
        $this->couponValidator
            ->method('validate')
            ->willReturn([
                'valid' => true,
                'type' => 'fixed',
                'value' => 150, // Fixed discount amount
                'min_orders' => 5
            ]);
        
        // Act - Order amount less than fixed discount
        $result = $this->discountService->calculateDiscount(1, 'FIXED150', 100);
        
        // Assert - Should not exceed order amount
        $this->assertEquals(100, $result['discount_amount']);
        $this->assertEquals(0, $result['final_amount']);
    }
    
    /**
     * Test: Percentage discount with maximum cap
     * @test
     */
    public function it_applies_maximum_discount_cap(): void
    {
        // Arrange
        $this->userRepository
            ->method('findById')
            ->willReturn(['id' => 1, 'type' => 'vip', 'order_count' => 10]);
            
        $this->couponValidator
            ->method('validate')
            ->willReturn([
                'valid' => true,
                'type' => 'percentage',
                'value' => 30, // 30% would be 300 on 1000 order
                'max_discount' => 200, // But capped at 200
                'min_orders' => 5
            ]);
        
        // Act
        $result = $this->discountService->calculateDiscount(1, 'BIG30', 1000);
        
        // Assert
        $this->assertEquals(200, $result['discount_amount']); // Capped at max_discount
        $this->assertEquals(800, $result['final_amount']);
    }
    
    /**
     * Test: Multiple scenarios with data provider
     * @test
     * @dataProvider discountScenarioProvider
     */
    public function it_handles_various_discount_scenarios(
        array $user, 
        array $coupon, 
        float $orderAmount, 
        float $expectedDiscount,
        bool $shouldApply
    ): void {
        // Arrange
        $this->userRepository
            ->method('findById')
            ->willReturn($user);
            
        $this->couponValidator
            ->method('validate')
            ->willReturn($coupon);
        
        // Act
        $result = $this->discountService->calculateDiscount(1, 'TEST', $orderAmount);
        
        // Assert
        $this->assertEquals($expectedDiscount, $result['discount_amount']);
        $this->assertEquals($shouldApply, $result['coupon_applied']);
    }
    
    public function discountScenarioProvider(): array
    {
        return [
            'vip_percentage_no_cap' => [
                ['type' => 'vip', 'order_count' => 10],
                ['valid' => true, 'type' => 'percentage', 'value' => 10, 'min_orders' => 5],
                100,
                10, // 10% of 100
                true
            ],
            'regular_eligible_fixed' => [
                ['type' => 'regular', 'order_count' => 8],
                ['valid' => true, 'type' => 'fixed', 'value' => 25, 'min_orders' => 5],
                100,
                25, // Fixed 25
                true
            ],
            'regular_ineligible' => [
                ['type' => 'regular', 'order_count' => 3],
                ['valid' => true, 'type' => 'percentage', 'value' => 20, 'min_orders' => 5],
                100,
                0, // Not eligible
                false
            ]
        ];
    }
}
```

**✅ Checkpoint 3**: Business logic testing dengan complete isolation menggunakan mocks

---

### **FASE 4: VALIDATION LOGIC TESTING** ⏱️ 15 menit

#### **Langkah 4.1: Buat Business Rule Validator**

Buat file `app/Validators/BusinessRuleValidator.php`:

```php
<?php

namespace App\Validators;

class BusinessRuleValidator
{
    /**
     * Check if user is eligible for premium features
     */
    public static function isEligibleForPremium(array $user): bool
    {
        // Must be active user
        if (!$user['active']) {
            return false;
        }
        
        // Must have premium subscription OR be VIP
        if ($user['subscription_type'] === 'premium' || $user['type'] === 'vip') {
            return true;
        }
        
        // Regular users need minimum orders and spending
        return $user['total_orders'] >= 10 && $user['total_spent'] >= 500000;
    }
    
    /**
     * Validate email format with business rules
     */
    public static function isValidBusinessEmail(string $email): array
    {
        $result = [
            'valid' => false,
            'errors' => []
        ];
        
        // Basic format check
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['errors'][] = 'Invalid email format';
            return $result;
        }
        
        // Business rule: No disposable email domains
        $disposableDomains = ['tempmail.com', '10minutemail.com', 'guerrillamail.com'];
        $domain = explode('@', $email)[1];
        
        if (in_array($domain, $disposableDomains)) {
            $result['errors'][] = 'Disposable email domains not allowed';
            return $result;
        }
        
        // Business rule: Company emails preferred for B2B
        $businessDomains = ['gmail.com', 'yahoo.com', 'hotmail.com'];
        $isPersonalEmail = in_array($domain, $businessDomains);
        
        $result['valid'] = true;
        $result['is_business_email'] = !$isPersonalEmail;
        
        return $result;
    }
    
    /**
     * Calculate loyalty tier based on user activity
     */
    public static function calculateLoyaltyTier(array $userStats): string
    {
        $orders = $userStats['total_orders'];
        $spent = $userStats['total_spent'];
        $months = $userStats['active_months'];
        
        // Diamond tier: High value, long-term customers
        if ($orders >= 50 && $spent >= 5000000 && $months >= 12) {
            return 'diamond';
        }
        
        // Gold tier: Good customers
        if ($orders >= 20 && $spent >= 2000000 && $months >= 6) {
            return 'gold';
        }
        
        // Silver tier: Regular customers
        if ($orders >= 10 && $spent >= 500000 && $months >= 3) {
            return 'silver';
        }
        
        // Bronze tier: New/low activity customers
        return 'bronze';
    }
}
```

#### **Langkah 4.2: Pure Unit Tests untuk Business Rules**

Buat file `tests/Unit/Validators/BusinessRuleValidatorTest.php`:

```php
<?php

namespace Tests\Unit\Validators;

use App\Validators\BusinessRuleValidator;
use PHPUnit\Framework\TestCase;

/**
 * Pure Unit Tests untuk Business Rule Validator
 * 
 * Testing business logic without any external dependencies
 */
class BusinessRuleValidatorTest extends TestCase
{
    /**
     * Test: Premium eligibility for different user types
     * @test
     * @dataProvider premiumEligibilityProvider
     */
    public function it_checks_premium_eligibility_correctly($user, $expected): void
    {
        // Act
        $result = BusinessRuleValidator::isEligibleForPremium($user);
        
        // Assert
        $this->assertEquals($expected, $result);
    }
    
    public function premiumEligibilityProvider(): array
    {
        return [
            'vip_user' => [
                ['active' => true, 'type' => 'vip', 'total_orders' => 5, 'total_spent' => 100000],
                true
            ],
            'premium_subscriber' => [
                ['active' => true, 'subscription_type' => 'premium', 'type' => 'regular', 'total_orders' => 5, 'total_spent' => 100000],
                true
            ],
            'qualified_regular_user' => [
                ['active' => true, 'type' => 'regular', 'total_orders' => 15, 'total_spent' => 600000],
                true
            ],
            'unqualified_regular_user' => [
                ['active' => true, 'type' => 'regular', 'total_orders' => 5, 'total_spent' => 100000],
                false
            ],
            'inactive_vip' => [
                ['active' => false, 'type' => 'vip', 'total_orders' => 20, 'total_spent' => 1000000],
                false
            ]
        ];
    }
    
    /**
     * Test: Email validation with business rules
     * @test
     */
    public function it_validates_business_email_correctly(): void
    {
        // Valid business email
        $result = BusinessRuleValidator::isValidBusinessEmail('john@company.com');
        $this->assertTrue($result['valid']);
        $this->assertTrue($result['is_business_email']);
        
        // Valid personal email
        $result = BusinessRuleValidator::isValidBusinessEmail('john@gmail.com');
        $this->assertTrue($result['valid']);
        $this->assertFalse($result['is_business_email']);
    }
    
    /**
     * Test: Disposable email rejection
     * @test
     */
    public function it_rejects_disposable_email_domains(): void
    {
        // Arrange
        $disposableEmail = 'test@tempmail.com';
        
        // Act
        $result = BusinessRuleValidator::isValidBusinessEmail($disposableEmail);
        
        // Assert
        $this->assertFalse($result['valid']);
        $this->assertContains('Disposable email domains not allowed', $result['errors']);
    }
    
    /**
     * Test: Invalid email format rejection
     * @test
     */
    public function it_rejects_invalid_email_format(): void
    {
        // Arrange
        $invalidEmails = ['invalid-email', 'test@', '@domain.com', 'test..test@domain.com'];
        
        foreach ($invalidEmails as $email) {
            // Act
            $result = BusinessRuleValidator::isValidBusinessEmail($email);
            
            // Assert
            $this->assertFalse($result['valid'], "Email {$email} should be invalid");
            $this->assertContains('Invalid email format', $result['errors']);
        }
    }
    
    /**
     * Test: Loyalty tier calculation
     * @test
     * @dataProvider loyaltyTierProvider
     */
    public function it_calculates_loyalty_tier_correctly($userStats, $expectedTier): void
    {
        // Act
        $tier = BusinessRuleValidator::calculateLoyaltyTier($userStats);
        
        // Assert
        $this->assertEquals($expectedTier, $tier);
    }
    
    public function loyaltyTierProvider(): array
    {
        return [
            'diamond_customer' => [
                ['total_orders' => 60, 'total_spent' => 6000000, 'active_months' => 18],
                'diamond'
            ],
            'gold_customer' => [
                ['total_orders' => 25, 'total_spent' => 2500000, 'active_months' => 8],
                'gold'
            ],
            'silver_customer' => [
                ['total_orders' => 15, 'total_spent' => 800000, 'active_months' => 4],
                'silver'
            ],
            'bronze_customer' => [
                ['total_orders' => 5, 'total_spent' => 200000, 'active_months' => 2],
                'bronze'
            ],
            'new_customer' => [
                ['total_orders' => 1, 'total_spent' => 50000, 'active_months' => 1],
                'bronze'
            ]
        ];
    }
    
    /**
     * Test: Edge cases for loyalty tier
     * @test
     */
    public function it_handles_edge_cases_for_loyalty_tier(): void
    {
        // Exactly at gold threshold
        $goldThreshold = ['total_orders' => 20, 'total_spent' => 2000000, 'active_months' => 6];
        $this->assertEquals('gold', BusinessRuleValidator::calculateLoyaltyTier($goldThreshold));
        
        // Just below gold threshold
        $belowGold = ['total_orders' => 19, 'total_spent' => 2000000, 'active_months' => 6];
        $this->assertEquals('silver', BusinessRuleValidator::calculateLoyaltyTier($belowGold));
        
        // Zero values
        $zeroStats = ['total_orders' => 0, 'total_spent' => 0, 'active_months' => 0];
        $this->assertEquals('bronze', BusinessRuleValidator::calculateLoyaltyTier($zeroStats));
    }
}
```

**✅ Checkpoint 4**: Business rule validation testing dengan pure unit testing approach

---

### **FASE 5: EXECUTION DAN PERFORMANCE ANALYSIS** ⏱️ 10 menit

#### **Langkah 5.1: Jalankan Pure Unit Tests**

```bash
# Run semua unit tests - should be very fast
php artisan test --testsuite=Unit

# Run dengan timing information
php artisan test --testsuite=Unit --verbose

# Run specific test class
php artisan test tests/Unit/Services/DiscountServiceTest.php

# Run dengan coverage (requires Xdebug)
php artisan test --testsuite=Unit --coverage-text

# Performance measurement untuk pure unit tests
time php artisan test --testsuite=Unit
```

#### **Langkah 5.2: Performance Expectations untuk Pure Unit Testing**

**Target Metrics:**
- **Total Execution Time**: < 2 seconds untuk semua unit tests
- **Individual Test Speed**: < 10ms per test method
- **Memory Usage**: < 50MB total
- **Database Calls**: 0 (zero database queries)
- **External Dependencies**: None

**Sample Output:**
```
PHPUnit 10.1.3 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.7
Configuration: /path/to/phpunit.xml

............................                                      28 / 28 (100%)

Time: 00:00.892, Memory: 24.00 MB

OK (28 tests, 89 assertions)
```

#### **Langkah 5.3: Validasi Pure Unit Testing Characteristics**

```bash
# Verify no database connections dalam unit tests
# Tambahkan di phpunit.xml untuk unit test suite:
<env name="DB_CONNECTION" value="null"/>

# Verify tests berjalan tanpa Laravel framework yang heavy
# Unit tests should work dengan minimal Laravel bootstrap
```

**Quality Checks:**
- ✅ No database queries logged
- ✅ Fast execution (< 2 seconds total)
- ✅ All tests use mocks for dependencies
- ✅ Tests dapat berjalan dalam isolation
- ✅ No external service calls

**✅ Checkpoint 5**: Pure unit tests executed dengan performance optimal

---

## **BEST PRACTICES PURE UNIT TESTING**

### **1. Dependency Injection untuk Testability**

```php
// ✅ Good - Dependencies injected, easy to mock
class OrderService
{
    public function __construct(
        private PaymentGatewayInterface $paymentGateway,
        private EmailServiceInterface $emailService
    ) {}
    
    public function processOrder(Order $order): bool
    {
        $charged = $this->paymentGateway->charge($order->total);
        
        if ($charged) {
            $this->emailService->sendConfirmation($order);
            return true;
        }
        
        return false;
    }
}

// ❌ Bad - Hard-coded dependencies, tidak testable
class OrderService  
{
    public function processOrder(Order $order): bool
    {
        $gateway = new PaymentGateway(); // Hard to test
        $emailService = new EmailService(); // Hard to mock
        
        // Business logic...
    }
}
```

### **2. Mock Verification Strategy**

```php
// ✅ Verify interactions (behavior testing)
$mock = $this->createMock(PaymentGatewayInterface::class);
$mock->expects($this->once())  // Verify called exactly once
     ->method('charge')
     ->with($this->equalTo(1000))  // Verify correct parameters
     ->willReturn(true);

// ✅ Verify call sequence
$mock->expects($this->exactly(2))
     ->method('log')
     ->withConsecutive(
         [$this->equalTo('payment_start')],
         [$this->equalTo('payment_success')]
     );
```

### **3. Test Organization dan Naming**

```php
// ✅ Group tests by behavior, descriptive names
class DiscountServiceTest extends TestCase
{
    // Happy path scenarios
    public function it_calculates_percentage_discount_for_vip_users(): void { }
    public function it_applies_fixed_discount_when_appropriate(): void { }
    
    // Error conditions
    public function it_throws_exception_for_invalid_user(): void { }
    public function it_returns_zero_for_invalid_coupon(): void { }
    
    // Edge cases
    public function it_handles_zero_order_amount(): void { }
    public function it_applies_maximum_discount_caps(): void { }
}
```

### **4. Data Provider Best Practices**

```php
// ✅ Use data providers untuk multiple scenarios
/**
 * @test
 * @dataProvider discountCalculationProvider
 */
public function it_calculates_discounts_correctly($input, $expected): void
{
    $result = $this->discountService->calculate($input);
    $this->assertEquals($expected, $result);
}

public function discountCalculationProvider(): array
{
    return [
        'vip_10_percent' => [
            ['amount' => 1000, 'user_type' => 'vip', 'coupon' => '10PERCENT'],
            ['discount' => 100, 'final_amount' => 900]
        ],
        'regular_fixed_50' => [
            ['amount' => 200, 'user_type' => 'regular', 'coupon' => 'FIXED50'],
            ['discount' => 50, 'final_amount' => 150]
        ]
    ];
}
```

---

## **TROUBLESHOOTING PURE UNIT TESTING**

### **Common Issues & Solutions**

#### **Issue 1: Tests are Slow**
```bash
# Problem: Unit tests taking too long (> 5 seconds)
# Solution: Check for hidden dependencies

# Verify no database connections
php artisan test --testsuite=Unit --verbose | grep "database"

# Check for file system or network calls
# Add debugging dalam test setUp():
protected function setUp(): void
{
    parent::setUp();
    error_reporting(E_ALL); // Catch any hidden I/O operations
}
```

#### **Issue 2: Mock Expectations Failing**
```php
// Problem: Mock expectations not met
// Error: Method was expected to be called 1 times, actually called 0 times

// Solution: Verify method names dan signatures
$mock = $this->createMock(PaymentGatewayInterface::class);
$mock->expects($this->once())
     ->method('processPayment')  // Check: method name correct?
     ->with($this->equalTo(100)) // Check: parameters match?
     ->willReturn(true);

// Debug mock calls:
$mock = $this->getMockBuilder(PaymentGatewayInterface::class)
          ->onlyMethods(['processPayment'])
          ->getMock();
```

#### **Issue 3: Laravel Dependencies in Unit Tests**
```php
// Problem: Unit tests requiring Laravel framework features
// Error: Class 'Illuminate\Support\Facades\DB' not found

// Solution: Remove Laravel dependencies atau use Integration tests
// ❌ Bad - Laravel dependency in unit test
public function test_calculation()
{
    $config = config('app.tax_rate'); // Laravel dependency
    // ...
}

// ✅ Good - Pure unit test
public function test_calculation()
{
    $calculator = new TaxCalculator(0.1); // Inject tax rate
    // ...
}
```

#### **Issue 4: Random Test Failures**
```php
// Problem: Tests fail randomly
// Solution: Remove time-based atau random dependencies

// ❌ Bad - Time dependency
public function test_is_business_hours()
{
    $now = now(); // Random based on execution time
    $result = BusinessHours::isOpen($now);
    // ...
}

// ✅ Good - Inject time dependency
public function test_is_business_hours()
{
    $testTime = Carbon::parse('2023-08-11 14:00:00');
    $result = BusinessHours::isOpen($testTime);
    // ...
}
```

---

## **EVALUASI DAN PENILAIAN**

### **Kriteria Penilaian Pure Unit Testing** (100 poin total)

| Aspek | Bobot | Kriteria Excellence | Kriteria Good | Kriteria Fair |
|-------|-------|-------------------|---------------|---------------|
| **Pure Isolation** | 30 poin | No database, no file system, no network. Complete isolation | Minimal external dependencies | Some external dependencies |
| **Mocking Implementation** | 25 poin | Proper use of mocks, stubs, fakes. Behavior verification | Good mocking, minor issues | Basic mocking |
| **Test Coverage** | 20 poin | 100% coverage pada business logic yang tested | 80-95% coverage | 60-80% coverage |
| **Performance** | 15 poin | < 2 seconds total execution, < 10ms per test | < 5 seconds total | < 10 seconds total |
| **Code Quality** | 10 poin | Clean, readable, good naming, proper AAA pattern | Mostly clean, minor issues | Basic quality |

### **Additional Assessment Criteria**
- **Dependency Injection**: Proper use of constructor injection ✅
- **Interface Usage**: Coding to interfaces, not implementations ✅
- **Test Independence**: Each test runs independently ✅
- **Business Logic Focus**: Tests focus on core business rules ✅

### **Deliverables**
- [ ] Helper function tests (`CurrencyHelperTest.php`)
- [ ] Service class tests dengan mocking (`DiscountServiceTest.php`)
- [ ] Business rule validator tests (`BusinessRuleValidatorTest.php`)
- [ ] Semua tests passing dengan execution time < 2 seconds
- [ ] Coverage report untuk unit tests
- [ ] Reflection document (300 kata) tentang pure unit testing experience

---

## **KESIMPULAN: PURE UNIT TESTING DALAM KONTEKS LARAVEL**

### **Key Takeaways** 🎯

1. **Pure Unit Testing** ideal untuk business logic yang dapat diisolasi
2. **Mocking essential** untuk memisahkan dependencies
3. **Fast execution** adalah karakteristik utama (< 2 seconds total)
4. **Limited applicability** dalam Laravel karena framework design
5. **High value** untuk algoritma, calculations, dan business rules

### **Kapan Menggunakan Pure Unit Testing** ✅

```
✅ Appropriate untuk:
- Helper functions dan utilities
- Mathematical calculations  
- Business rule validations
- String/data transformations
- Algorithm implementations
- Service classes (dengan mocking)

❌ TIDAK appropriate untuk:
- Eloquent Model testing (gunakan Integration Testing)
- HTTP endpoint testing (gunakan Feature Testing)  
- Database operations (gunakan Integration Testing)
- File upload/download (gunakan Integration Testing)
- Email sending (gunakan Integration Testing)
```

### **Industry Reality Check** 📊

```
Typical Laravel Project Testing Distribution:
📁 tests/Unit/         (5-15% of total tests)
├── Helper function tests
├── Service tests (dengan mocks)
├── Utility class tests  
└── Business rule tests

📁 tests/Feature/      (85-95% of total tests)
├── HTTP endpoint tests
├── Database integration tests
├── Workflow tests
└── API tests
```

### **Practical Guidance** 🎓

1. **Start Simple**: Mulai dengan helper functions dan utilities
2. **Use Mocking Sparingly**: Only when truly needed untuk isolation
3. **Focus on Business Value**: Test logic yang business-critical
4. **Combine with Integration**: Pure unit testing melengkapi, tidak menggantikan integration testing
5. **Measure Performance**: Unit tests harus fast (<2 seconds total)

### **Next Steps** 🚀

Lanjutkan ke **MODUL_PRAKTIKUM_INTEGRATION_TESTING.md** untuk:
- Model integration testing dengan database
- HTTP endpoint testing
- Feature testing
- Complete application workflow testing

---

**🎯 Remember: Pure Unit Testing valuable untuk specific use cases, tapi Integration Testing tetap backbone testing strategy di Laravel development!**
