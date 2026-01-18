<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use App\Models\Item;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Staff
        User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@staff.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Category
        $category = Category::create([
            'name' => 'Elektronik',
            'description' => 'Barang elektronik',
        ]);

        // Location
        $location = Location::create([
            'name' => 'Gudang Utama',
            'description' => 'Penyimpanan utama',
        ]);

        // Item
        Item::create([
            'category_id' => $category->id,
            'location_id' => $location->id,
            'name' => 'Laptop Dell',
            'code' => 'LPT-001',
            'description' => 'Laptop untuk guru',
            'stock' => 10,
            'condition' => 'good',
        ]);
    }
}
