<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiSecurityAndValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_access_returns_401(): void
    {
        $response = $this->getJson('/api/inventory');
        $response->assertStatus(401);
    }

    public function test_po_creation_validation_fails_for_empty_payload(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/purchase-orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['supplier_id', 'items']);
    }
}