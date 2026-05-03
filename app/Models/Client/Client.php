<?php

namespace App\Models\Client;

use App\Models\Booking\Booking;
use App\Models\Content\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Client extends Authenticatable
{
    use SoftDeletes, HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\ClientFactory::new();
    }

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'login_disabled',
        'is_company',
        'business_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'login_disabled'    => 'boolean',
        'is_company'        => 'boolean',
    ];

    /**
     * تشفير كلمة المرور تلقائياً — يتجاهل القيم المشفرة مسبقاً.
     */
    public function setPasswordAttribute($value): void
    {
        if ($value) {
            // إذا كانت القيمة مشفرة مسبقاً بـ bcrypt/argon لا تعيد تشفيرها
            $isHashed = str_starts_with($value, '$2y$')
                     || str_starts_with($value, '$2a$')
                     || str_starts_with($value, '$argon2');

            $this->attributes['password'] = $isHashed ? $value : Hash::make($value);
        }
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /** الاشتراكات الشهرية (باقات الإعلانات) */
    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription\Subscription::class);
    }

    public function messages()
    {
        return $this->hasMany(ClientMessage::class);
    }

    public function photos()
    {
        return $this->hasMany(ClientPhoto::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function hasPassword(): bool
    {
        return !empty($this->password);
    }

    public function selectedPhotos()
    {
        return $this->hasMany(ClientSelectedPhoto::class);
    }

    public function mediaSeen()
    {
        return $this->hasMany(ClientMediaSeen::class);
    }

    public function messagesSeen()
    {
        return $this->hasOne(ClientMessagesSeen::class);
    }
}
