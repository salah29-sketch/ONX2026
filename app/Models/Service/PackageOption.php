<?php
namespace App\Models\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageOption extends Model
{
    protected $fillable = [
        'package_id',
        'label',          // "عدد صور الألبوم"
        'type',           // 'boolean' | 'select' | 'number'
        'price_effect',   // 'fixed' | 'per_unit' | 'free'
        'price',          // السعر الثابت أو سعر الوحدة
        'options',        // JSON — للـ select: [['label'=>'صباح','price'=>0], ...]
        'min',            // للـ number: الحد الأدنى
        'max',            // للـ number: الحد الأقصى
        'default_value',
        'is_required',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'options'     => 'array',
        'is_required' => 'boolean',
        'is_active'   => 'boolean',
        'min'         => 'integer',
        'max'         => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function calculatePrice(mixed $value): float
    {
        if ($this->price_effect === 'free' || ! $this->price) {
            return 0;
        }

        if ($this->price_effect === 'per_unit') {
            return (float) $this->price * (int) $value;
        }

        // fixed — إذا اختار العميل هذا الخيار يُضاف السعر
        return (float) $this->price;
    }
}