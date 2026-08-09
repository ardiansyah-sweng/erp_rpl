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
    
    public static function discountScenarioProvider(): array
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
}
