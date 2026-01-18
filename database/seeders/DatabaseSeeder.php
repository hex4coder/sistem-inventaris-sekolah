<?php

namespace Database\Seeders;


use App\Models\Setting;
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
            'name' => 'Test Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Settings
        Setting::create(['key' => 'school_name', 'value' => 'SD Negeri 1 Campalagian']);
        Setting::create(['key' => 'school_logo', 'value' => null]);
        Setting::create(['key' => 'academic_year', 'value' => '2023/2024']);
        Setting::create(['key' => 'semester', 'value' => 'Ganjil']);

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
