<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientMessagesSeen extends Model
{
    protected $table = 'client_messages_seen';

    protected $fillable = ['client_id', 'last_seen_at'];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
