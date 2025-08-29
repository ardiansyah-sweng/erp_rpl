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
