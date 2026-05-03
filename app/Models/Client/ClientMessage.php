<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientMessage extends Model
{
    protected $fillable = ['client_id', 'subject', 'message', 'admin_reply', 'admin_replied_at'];

    protected $casts = [
        'admin_read_at'   => 'datetime',
        'admin_replied_at' => 'datetime',
    ];

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
