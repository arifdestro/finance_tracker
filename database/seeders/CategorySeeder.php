<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Income
            ['name' => 'Gaji', 'type' => 'income', 'icon' => 'currency-dollar', 'color' => '#10B981'],
            ['name' => 'Bonus', 'type' => 'income', 'icon' => 'gift', 'color' => '#34D399'],
            ['name' => 'Investasi', 'type' => 'income', 'icon' => 'chart-bar', 'color' => '#059669'],
            
            // Expense
            ['name' => 'Makanan & Minuman', 'type' => 'expense', 'icon' => 'shopping-cart', 'color' => '#F43F5E'],
            ['name' => 'Transportasi', 'type' => 'expense', 'icon' => 'truck', 'color' => '#F59E0B'],
            ['name' => 'Tagihan', 'type' => 'expense', 'icon' => 'document-text', 'color' => '#8B5CF6'],
            ['name' => 'Belanja', 'type' => 'expense', 'icon' => 'shopping-bag', 'color' => '#EC4899'],
            ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => 'heart', 'color' => '#EF4444'],
            ['name' => 'Pendidikan', 'type' => 'expense', 'icon' => 'academic-cap', 'color' => '#3B82F6'],
            ['name' => 'Hiburan', 'type' => 'expense', 'icon' => 'film', 'color' => '#14B8A6'],
            ['name' => 'Lain-lain', 'type' => 'expense', 'icon' => 'sparkles', 'color' => '#6B7280'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'type' => $category['type'],
                'icon' => $category['icon'],
                'color' => $category['color'],
                'is_default' => true,
                'user_id' => null,
            ]);
        }
    }
}
