<?php

namespace App\Services;

use App\Models\Client\Client;

class ClientService
{
    /**
     * Find an existing client by email or create a new one.
     */
    public function findOrCreate(array $data): Client
    {
        return Client::firstOrCreate(
            ['email' => $data['email']],
            [
                'name'  => $data['name'],
                'phone' => $data['phone'] ?? null,
            ]
        );
    }

    /**
     * Update an existing client's information.
     */
    public function update(Client $client, array $data): Client
    {
        $client->update(array_filter([
            'name'  => $data['name'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
        ]));

        return $client->fresh();
    }
}
