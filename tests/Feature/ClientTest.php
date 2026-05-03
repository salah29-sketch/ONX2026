<?php

namespace Tests\Feature;

use App\Models\Client\Client;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Admin\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

   protected function setUp(): void
{
    parent::setUp();
    $this->admin = User::factory()->create(['password' => Hash::make('password')]);
    \Illuminate\Support\Facades\Gate::before(fn() => true);
}

    public function test_admin_can_view_clients_list(): void
    {
        Client::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/clients');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_client(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', [
            'name'  => 'أحمد محمد',
            'phone' => '0600000000',
            'email' => 'ahmed@test.com',
        ]);

        $this->assertDatabaseHas('clients', ['email' => 'ahmed@test.com']);
        $response->assertRedirect();
    }

    public function test_client_creation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', [
            'phone' => '0600000000',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_client_creation_requires_phone(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', [
            'name' => 'أحمد',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_client_email_must_be_valid(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', [
            'name'  => 'أحمد',
            'phone' => '0600000000',
            'email' => 'not-valid-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_update_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/clients/{$client->id}", [
            'name'  => 'اسم محدّث',
            'phone' => '0699999999',
            'email' => $client->email,
        ]);

        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'اسم محدّث']);
        $response->assertRedirect();
    }

    public function test_admin_can_delete_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/clients/{$client->id}");

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
        $response->assertRedirect();
    }
}
