<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_staff_can_request_borrowing(): void
    {
        $staff = \App\Models\User::factory()->create(['role' => 'staff']);
        $category = \App\Models\Category::create(['name' => 'Cat 1']);
        $location = \App\Models\Location::create(['name' => 'Loc 1']);
        $item = \App\Models\Item::create([
            'name' => 'Test Item',
            'code' => 'TEST-001',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'stock' => 10,
            'condition' => 'good',
        ]);

        $response = $this->actingAs($staff)->post('/borrowings', [
            'item_id' => $item->id,
            'quantity' => 2,
            'borrow_date' => now()->addDay()->toDateString(),
            'due_date' => now()->addDays(2)->toDateString(),
            'notes' => 'Test borrow',
        ]);

        $response->assertRedirect(route('borrowings.index'));
        $this->assertDatabaseHas('borrowings', ['item_id' => $item->id, 'quantity' => 2, 'status' => 'pending']);
    }

    public function test_admin_can_approve_borrowing_and_stock_decreases(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $category = \App\Models\Category::create(['name' => 'Cat 1']);
        $location = \App\Models\Location::create(['name' => 'Loc 1']);
        $item = \App\Models\Item::create([
            'name' => 'Test Item',
            'code' => 'TEST-002',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'stock' => 10,
            'condition' => 'good',
        ]);

        $borrowing = \App\Models\Borrowing::create([
            'user_id' => $admin->id,
            'item_id' => $item->id,
            'quantity' => 2,
            'borrow_date' => now(),
            'due_date' => now()->addDay(),
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->put("/borrowings/{$borrowing->id}", ['status' => 'approved']);

        $this->assertDatabaseHas('borrowings', ['id' => $borrowing->id, 'status' => 'approved']);
        $this->assertDatabaseHas('items', ['id' => $item->id, 'stock' => 8]);
    }

    public function test_admin_can_return_borrowing_and_stock_increases(): void
    {
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $category = \App\Models\Category::create(['name' => 'Cat 1']);
        $location = \App\Models\Location::create(['name' => 'Loc 1']);
        $item = \App\Models\Item::create([
            'name' => 'Test Item',
            'code' => 'TEST-003',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'stock' => 8,
            'condition' => 'good',
        ]);

        $borrowing = \App\Models\Borrowing::create([
            'user_id' => $admin->id,
            'item_id' => $item->id,
            'quantity' => 2,
            'borrow_date' => now(),
            'due_date' => now()->addDay(),
            'status' => 'approved',
        ]);

        $this->actingAs($admin)->put("/borrowings/{$borrowing->id}", ['status' => 'returned']);

        $this->assertDatabaseHas('borrowings', ['id' => $borrowing->id, 'status' => 'returned']);
        $this->assertDatabaseHas('items', ['id' => $item->id, 'stock' => 10]);
    }
}
