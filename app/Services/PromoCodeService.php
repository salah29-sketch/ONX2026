<?php

namespace App\Services;

use App\Models\Promo\PromoCode;
use Illuminate\Support\Facades\DB;

class PromoCodeService
{
    /**
     * Validate and apply promo code. Returns ['valid' => bool, 'message' => ?, 'discount_amount' => ?, 'final_price' => ?].
     * Server-side only; do not trust frontend.
     */
    public function validateAndApply(string $code, float $orderTotal): array
    {
        $promo = PromoCode::where('code', $code)->first();

        if (!$promo) {
            return ['valid' => false, 'message' => 'كود التخفيض غير صحيح.'];
        }

        if (!$promo->is_active) {
            return ['valid' => false, 'message' => 'هذا الكود غير مفعّل.'];
        }

        if ($promo->max_uses !== null && $promo->used_count >= $promo->max_uses) {
            return ['valid' => false, 'message' => 'تم استنفاد استخدام هذا الكود.'];
        }

        if (!$promo->meetsMinOrder($orderTotal)) {
            $min = (float) $promo->min_order_value;
            return ['valid' => false, 'message' => "الحد الأدنى للطلب لهذا الكود: " . number_format($min, 0) . " د.ج."];
        }

        $discount = $promo->calculateDiscount($orderTotal);
        $final = max(0, round($orderTotal - $discount, 2));

        return [
            'valid'          => true,
            'message'        => null,
            'promo_code_id'  => $promo->id,
            'discount_amount' => $discount,
            'final_price'    => $final,
        ];
    }

    /**
     * Increment used_count atomically — يمنع تجاوز max_uses عند الطلبات المتزامنة.
     */
    public function incrementUsed(int $promoCodeId): void
    {
        PromoCode::where('id', $promoCodeId)
            ->where(function ($q) {
                $q->whereNull('max_uses')
                  ->orWhereRaw('used_count < max_uses');
            })
            ->increment('used_count');
    }
}
