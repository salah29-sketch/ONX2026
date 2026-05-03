<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $type
 * @property string $method
 */
class BookingPayment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'type',
        'method',
        'reference',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'deposit'  => 'دفعة أولى',
            'partial'  => 'دفعة جزئية',
            'final'    => 'دفعة نهائية',
            'full'     => 'دفع كامل',
            default    => $this->type,
        };
    }

    public function methodLabel(): string
    {
        return match ($this->method) {
            'cash'          => 'نقدًا',
            'bank_transfer' => 'تحويل بنكي',
            'other'         => 'أخرى',
            default         => $this->method,
        };
    }
}
