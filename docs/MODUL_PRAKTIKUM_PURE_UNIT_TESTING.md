# **MODUL PRAKTIKUM PURE UNIT TESTING DALAM LARAVEL**

---

## **INFORMASI PRAKTIKUM**
- **Mata Kuliah**: Rekayasa Perangkat Lunak  
- **Topik**: Pure Unit Testing - Isolated Component Testing
- **Durasi**: 45 menit
- **Tingkat**: Intermediate
- **Prerequisites**: Pemahaman konsep testing, mocking, dan dependency injection

---

## **TUJUAN PEMBELAJARAN**

Setelah menyelesaikan praktikum pure unit testing ini, mahasiswa diharapkan mampu:

1. **Memahami konsep Pure Unit Testing** yang truly isolated
2. **Membedakan Pure Unit Testing dari Integration Testing** 
3. **Mengimplementasikan Mocking dan Stubbing** untuk dependencies
4. **Menguji business logic** tanpa database dependencies
5. **Menggunakan Test Doubles** (Mocks, Stubs, Fakes) dengan benar
6. **Mengidentifikasi kapan pure unit testing appropriate** dalam Laravel

---

## **PENDAHULUAN**

### **Realitas Pure Unit Testing dalam Laravel**

Pure Unit Testing di Laravel **sangat jarang digunakan** karena:

1. **Framework Architecture**: Laravel dirancang dengan tight coupling
2. **Eloquent Models**: Hampir selalu memerlukan database
3. **Industry Practice**: Integration testing lebih praktis dan valuable
4. **ROI (Return on Investment)**: Pure unit testing memerlukan effort tinggi dengan benefit minimal

### **Kapan Pure Unit Testing Masuk Akal?**

Pure unit testing hanya praktis untuk komponen yang **truly isolated**:

| Component Type | Example | Alasan |
|----------------|---------|---------|
| **Helper Functions** | `formatCurrency($amount)` | No external dependencies |
| **Mathematical Operations** | `calculateTax($price, $rate)` | Pure computation |
| **String Utilities** | `slugify($title)` | Deterministic output |
| **Business Rules** | `isEligibleForDiscount($user)` | Logic-only operations |
| **Data Transformers** | `transformApiResponse($data)` | Input → Output mapping |

### **Pure Unit vs Integration Testing**

```php
// Pure Unit Testing (Rare in Laravel)
class TaxCalculatorTest extends TestCase 
{
    public function test_calculate_vat()
    {
        $calculator = new TaxCalculator();
        $result = $calculator->calculateVAT(100, 0.1);
        $this->assertEquals(10, $result);
    }
}

// Integration Testing (Common in Laravel)  
class BranchTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_branch_creation()
    {
        $branch = Branch::factory()->create();
        $this->assertDatabaseHas('branches', ['id' => $branch->id]);
    }
}
```

---

## **DASAR TEORI PURE UNIT TESTING**

### **1. Characteristics of Pure Unit Testing**

```php
// ✅ Pure Unit Test - Fast, Isolated, Repeatable
class StringHelperTest extends TestCase
{
    public function test_slug_generation()
    {
        $helper = new StringHelper();
        $result = $helper->slug('Hello World 123');
        $this->assertEquals('hello-world-123', $result);
    }
}

// ❌ Not Pure Unit Test - Has database dependency
class UserTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_creation()
    {
        $user = User::create(['name' => 'John']);
        $this->assertEquals('John', $user->name);
    }
}
```

### **2. Test Doubles: Mocks, Stubs, and Fakes**

```php
// Mock - Verifies behavior (interactions)
$mock = $this->createMock(PaymentGateway::class);
$mock->expects($this->once())
     ->method('charge')
     ->with(100)
     ->willReturn(true);

// Stub - Provides predefined responses
$stub = $this->createStub(ExchangeRate::class);
$stub->method('getRate')
     ->willReturn(1.5);

// Fake - Working implementation for testing
$fake = new FakeEmailService();
```

### **3. Dependency Injection for Testing**

```php
class OrderProcessor
{
    public function __construct(
        private PaymentGateway $gateway,
        private EmailService $emailService
    ) {}
    
    public function processOrder(Order $order): bool
    {
        $charged = $this->gateway->charge($order->total);
        
        if ($charged) {
            $this->emailService->sendConfirmation($order);
            return true;
        }
        
        return false;
    }
}
```

---

## **IMPLEMENTASI PURE UNIT TESTING**

### **FASE 1: HELPER FUNCTION TESTING** ⏱️ 15 menit

#### **Langkah 1.1: Buat Helper Class untuk Testing**

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
     * Parse currency string to amount
     */
    public static function parse(string $currencyString): float
    {
        // Remove currency symbols and formatting
        $cleaned = preg_replace('/[^\d.,]/', '', $currencyString);
        $cleaned = str_replace(',', '', $cleaned);
        
        return (float) $cleaned;
    }
    
    /**
     * Calculate percentage
     */
    public static function calculatePercentage(float $amount, float $percentage): float
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Percentage must be between 0 and 100');
        }
        
        return round($amount * ($percentage / 100), 2);
    }
}
```

#### **Langkah 1.2: Pure Unit Tests untuk Helper**

Buat file `tests/Unit/Helpers/CurrencyHelperTest.php`:

```php
<?php

namespace Tests\Unit\Helpers;

use App\Helpers\CurrencyHelper;
use PHPUnit\Framework\TestCase; // Note: Not extending Laravel's TestCase
use InvalidArgumentException;

/**
 * Pure Unit Tests untuk CurrencyHelper
 * 
 * Characteristics:
 * - No database dependencies
 * - No Laravel framework dependencies  
 * - Fast execution (< 1ms per test)
 * - Isolated and repeatable
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
     * Test: Exception for unsupported currency
     * @test
     */
    public function it_throws_exception_for_unsupported_currency(): void
    {
        // Arrange & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported currency: EUR');
        
        // Act
        CurrencyHelper::format(100, 'EUR');
    }
    
    /**
     * Test: Parse currency string to amount
     * @test
     * @dataProvider currencyParsingProvider
     */
    public function it_parses_currency_string_to_amount($input, $expected): void
    {
        // Act
        $result = CurrencyHelper::parse($input);
        
        // Assert
        $this->assertEquals($expected, $result);
    }
    
    public function currencyParsingProvider(): array
    {
        return [
            'idr_format' => ['Rp 1.500.000', 1500000],
            'usd_format' => ['$1,234.56', 1234.56],
            'numbers_only' => ['123456', 123456],
            'with_spaces' => ['Rp 100 000', 100000]
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
            'hundred_percent' => [500, 100, 500.0],
            'decimal_percentage' => [1000, 12.5, 125.0]
        ];
    }
    
    /**
     * Test: Exception for invalid percentage
     * @test
     * @dataProvider invalidPercentageProvider
     */
    public function it_throws_exception_for_invalid_percentage($invalidPercentage): void
    {
        // Arrange & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Percentage must be between 0 and 100');
        
        // Act
        CurrencyHelper::calculatePercentage(1000, $invalidPercentage);
    }
    
    public function invalidPercentageProvider(): array
    {
        return [
            'negative' => [-1],
            'over_hundred' => [101],
            'way_over' => [999]
        ];
    }
}
```

---

### **FASE 2: BUSINESS LOGIC TESTING WITH MOCKS** ⏱️ 20 menit

#### **Langkah 2.1: Buat Service Class dengan Dependencies**

Buat file `app/Services/DiscountService.php`:

```php
<?php

namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use App\Contracts\CouponValidatorInterface;

class DiscountService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private CouponValidatorInterface $couponValidator
    ) {}
    
    /**
     * Calculate discount for user
     */
    public function calculateDiscount(int $userId, string $couponCode, float $orderAmount): float
    {
        // Get user data
        $user = $this->userRepository->findById($userId);
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        // Validate coupon
        $coupon = $this->couponValidator->validate($couponCode);
        
        if (!$coupon['valid']) {
            return 0;
        }
        
        // Check user eligibility  
        if (!$this->isUserEligible($user, $coupon)) {
            return 0;
        }
        
        // Calculate discount based on type
        return $this->calculateDiscountAmount($orderAmount, $coupon);
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
            $discount = $orderAmount * ($coupon['value'] / 100);
            return min($discount, $coupon['max_discount'] ?? $discount);
        }
        
        if ($coupon['type'] === 'fixed') {
            return min($coupon['value'], $orderAmount);
        }
        
        return 0;
    }
}
```

#### **Langkah 2.2: Buat Interface Contracts**

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

#### **Langkah 2.3: Pure Unit Tests dengan Mocks**

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
 * Using mocks to isolate business logic from dependencies
 */
class DiscountServiceTest extends TestCase
{
    private DiscountService $discountService;
    private MockObject $userRepository;
    private MockObject $couponValidator;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mocks
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->couponValidator = $this->createMock(CouponValidatorInterface::class);
        
        // Inject mocks into service
        $this->discountService = new DiscountService(
            $this->userRepository,
            $this->couponValidator
        );
    }
    
    /**
     * Test: Calculate percentage discount for VIP user
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
                'max_discount' => 50,
                'min_orders' => 10
            ]);
        
        // Act
        $discount = $this->discountService->calculateDiscount(1, 'VIP20', 100);
        
        // Assert
        $this->assertEquals(20, $discount); // 20% of 100
    }
    
    /**
     * Test: No discount for invalid coupon
     * @test
     */
    public function it_returns_zero_for_invalid_coupon(): void
    {
        // Arrange
        $this->userRepository
            ->method('findById')
            ->willReturn(['id' => 1, 'type' => 'regular', 'order_count' => 5]);
            
        $this->couponValidator
            ->method('validate')
            ->willReturn(['valid' => false]);
        
        // Act
        $discount = $this->discountService->calculateDiscount(1, 'INVALID', 100);
        
        // Assert
        $this->assertEquals(0, $discount);
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
     * Test: Regular user with insufficient orders gets no discount
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
        $discount = $this->discountService->calculateDiscount(2, 'REGULAR15', 100);
        
        // Assert
        $this->assertEquals(0, $discount);
    }
    
    /**
     * Test: Fixed amount discount with order limit
     * @test
     */
    public function it_calculates_fixed_discount_with_order_limit(): void
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
        
        // Act - Order amount is less than fixed discount
        $discount = $this->discountService->calculateDiscount(1, 'FIXED150', 100);
        
        // Assert - Should not exceed order amount
        $this->assertEquals(100, $discount);
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
                'max_discount' => 100, // But capped at 100
                'min_orders' => 5
            ]);
        
        // Act
        $discount = $this->discountService->calculateDiscount(1, 'BIG30', 1000);
        
        // Assert
        $this->assertEquals(100, $discount); // Capped at max_discount
    }
}
```

---

### **FASE 3: TESTING EDGE CASES** ⏱️ 10 menit

#### **Langkah 3.1: Data Providers untuk Boundary Testing**

```php
/**
 * Test: Multiple discount scenarios using data provider
 * @test
 * @dataProvider discountScenarioProvider
 */
public function it_handles_various_discount_scenarios(
    array $user, 
    array $coupon, 
    float $orderAmount, 
    float $expectedDiscount
): void {
    // Arrange
    $this->userRepository
        ->method('findById')
        ->willReturn($user);
        
    $this->couponValidator
        ->method('validate')
        ->willReturn($coupon);
    
    // Act
    $discount = $this->discountService->calculateDiscount(1, 'TEST', $orderAmount);
    
    // Assert
    $this->assertEquals($expectedDiscount, $discount);
}

public function discountScenarioProvider(): array
{
    return [
        'vip_percentage_no_cap' => [
            ['type' => 'vip', 'order_count' => 10],
            ['valid' => true, 'type' => 'percentage', 'value' => 10, 'min_orders' => 5],
            100,
            10 // 10% of 100
        ],
        'regular_eligible_fixed' => [
            ['type' => 'regular', 'order_count' => 8],
            ['valid' => true, 'type' => 'fixed', 'value' => 25, 'min_orders' => 5],
            100,
            25 // Fixed 25
        ],
        'regular_ineligible' => [
            ['type' => 'regular', 'order_count' => 3],
            ['valid' => true, 'type' => 'percentage', 'value' => 20, 'min_orders' => 5],
            100,
            0 // Not eligible
        ],
        'zero_order_amount' => [
            ['type' => 'vip', 'order_count' => 10],
            ['valid' => true, 'type' => 'percentage', 'value' => 20, 'min_orders' => 5],
            0,
            0 // 20% of 0 is 0
        ]
    ];
}
```

---

## **RUNNING PURE UNIT TESTS**

### **Execution Commands**

```bash
# Run only pure unit tests (fast)
php artisan test tests/Unit/Helpers/ --stop-on-failure

# Run with coverage for specific directory
php artisan test tests/Unit/Services/ --coverage-text

# Run single test class
php artisan test tests/Unit/Services/DiscountServiceTest.php

# Run with timing information
php artisan test tests/Unit/ --verbose
```

### **Performance Expectations**

```
Pure Unit Test Performance Targets:
⚡ Execution Time: < 50ms per test class
⚡ Total Suite: < 2 seconds for all unit tests
⚡ No Database: 0 database queries
⚡ Memory Usage: < 50MB total
```

---

## **BEST PRACTICES PURE UNIT TESTING**

### **1. Dependency Injection Strategy**

```php
// ✅ Good - Testable with mocks
class OrderService
{
    public function __construct(
        private PaymentGateway $gateway,
        private EmailService $emailService
    ) {}
}

// ❌ Bad - Hard to test
class OrderService  
{
    public function process(Order $order)
    {
        $gateway = new PaymentGateway(); // Hard-coded dependency
        $gateway->charge($order->total);
    }
}
```

### **2. Mock Verification**

```php
// ✅ Verify interactions (behavior testing)
$mock->expects($this->once())
     ->method('charge')
     ->with($this->equalTo(100));

// ✅ Verify call sequence
$mock->expects($this->exactly(2))
     ->method('log')
     ->withConsecutive(['start'], ['end']);
```

### **3. Test Organization**

```php
// Group tests by behavior, not by method
class DiscountServiceTest extends TestCase
{
    // Happy path tests
    public function test_successful_percentage_discount() { }
    public function test_successful_fixed_discount() { }
    
    // Error conditions
    public function test_invalid_user_throws_exception() { }
    public function test_invalid_coupon_returns_zero() { }
    
    // Edge cases
    public function test_zero_amount_returns_zero() { }
    public function test_discount_caps_work_correctly() { }
}
```

---

## **KESIMPULAN PURE UNIT TESTING**

### **Key Takeaways** 🎯

1. **Pure Unit Testing ideal untuk isolated business logic**
2. **Mocking essential untuk dependency isolation**
3. **Fast execution dan no external dependencies**
4. **Focuses on behavior verification, not state**
5. **Limited applicability dalam Laravel due to framework design**

### **When to Use Pure Unit Testing** ✅

```
Appropriate untuk:
✅ Helper functions dan utilities
✅ Mathematical calculations
✅ Business rule validations  
✅ Data transformations
✅ Algorithm implementations

Tidak appropriate untuk:
❌ Eloquent Model testing
❌ Database operations
❌ HTTP endpoint testing
❌ Laravel framework integrations
❌ File system operations
```

### **Industry Reality** 📊

```
Typical Laravel Project Testing Distribution:
📁 tests/Unit/         (5-15% of total tests)
├── Helper tests
├── Service tests (with mocks)
└── Utility class tests

📁 tests/Feature/      (85-95% of total tests)
├── HTTP endpoint tests
├── Database integration tests
└── Workflow tests
```

**🎯 Pure Unit Testing valuable untuk specific use cases, tapi Integration Testing tetap menjadi backbone testing strategy di Laravel!**
