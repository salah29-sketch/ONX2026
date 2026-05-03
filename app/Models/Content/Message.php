<?php

namespace App\Models\Content;

use App\Models\Client\Client;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'client_id', 'name', 'phone', 'email',
        'subject', 'message', 'admin_reply', 'admin_replied_at',
        'admin_read_at', 'status',
    ];

    protected $casts = [
        'admin_replied_at' => 'datetime',
        'admin_read_at'    => 'datetime',
    ];

    // ─── Scopes ───

    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    // ─── Relationships ───

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }



    // ─── Helpers ───

    public function displayName(): string
    {
        if ($this->client) {
            return $this->client->name ?? $this->name ?? 'زائر';
        }

        return $this->name ?? 'زائر';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'new'     => 'جديد',
            'read'    => 'مقروء',
            'replied' => 'تم الرد',
            'closed'  => 'مغلق',
            default   => $this->status,
        };
    }

    public function isReplied(): bool
    {
        return $this->status === 'replied' && $this->admin_reply !== null;
    }
}
