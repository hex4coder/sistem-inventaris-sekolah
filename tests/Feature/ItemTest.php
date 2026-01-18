<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_admin_can_create_items(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $category = \App\Models\Category::create(['name' => 'Cat 1']);
        $location = \App\Models\Location::create(['name' => 'Loc 1']);

        $response = $this->actingAs($admin)->post('/items', [
            'name' => 'Test Item',
            'code' => 'TEST-001',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'stock' => 10,
            'condition' => 'good',
        ]);

        $response->assertRedirect(route('items.index'));
        $this->assertDatabaseHas('items', ['code' => 'TEST-001']);
    }

    public function test_staff_cannot_create_items(): void
    {
        $staff = \App\Models\User::factory()->create(['role' => 'staff']);
        $category = \App\Models\Category::create(['name' => 'Cat 1']);
        $location = \App\Models\Location::create(['name' => 'Loc 1']);

        $response = $this->actingAs($staff)->post('/items', [
            'name' => 'Test Item',
            'code' => 'TEST-001',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'stock' => 10,
            'condition' => 'good',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('items', ['code' => 'TEST-001']);
    }

    public function test_staff_can_view_items(): void
    {
        $staff = \App\Models\User::factory()->create(['role' => 'staff']);
        $category = \App\Models\Category::create(['name' => 'Cat 1']);
        $location = \App\Models\Location::create(['name' => 'Loc 1']);
        $item = \App\Models\Item::create([
            'name' => 'Existing Item',
            'code' => 'EXT-001',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'stock' => 5,
            'condition' => 'good',
        ]);

        $response = $this->actingAs($staff)->get('/items');
        $response->assertStatus(200);
        $response->assertSee('Existing Item');
    }
}
