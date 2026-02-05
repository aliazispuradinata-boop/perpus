<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fiction',
                'slug' => 'fiction',
                'description' => 'Novels and short stories from various genres',
                'icon' => '📖',
                'color' => '#FF6B35',
                'display_order' => 1,
            ],
            [
                'name' => 'Self-Help',
                'slug' => 'self-help',
                'description' => 'Books about personal development and self-improvement',
                'icon' => '💪',
                'color' => '#FFA500',
                'display_order' => 2,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Books about business, entrepreneurship, and management',
                'icon' => '💼',
                'color' => '#FF8C42',
                'display_order' => 3,
            ],
            [
                'name' => 'Technology & AI',
                'slug' => 'technology-ai',
                'description' => 'Books about technology, programming, and artificial intelligence',
                'icon' => '💻',
                'color' => '#E8A741',
                'display_order' => 4,
            ],
            [
                'name' => 'Fantasy & Sci-Fi',
                'slug' => 'fantasy-sci-fi',
                'description' => 'Fantasy and Science Fiction novels',
                'icon' => '🚀',
                'color' => '#C67C4E',
                'display_order' => 5,
            ],
            [
                'name' => 'Biography',
                'slug' => 'biography',
                'description' => 'Biographies and memoirs of notable people',
                'icon' => '👤',
                'color' => '#B8860B',
                'display_order' => 6,
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'description' => 'Books about health, fitness, and wellness',
                'icon' => '🏥',
                'color' => '#CD853F',
                'display_order' => 7,
            ],
            [
                'name' => 'Psychology',
                'slug' => 'psychology',
                'description' => 'Books about psychology and human behavior',
                'icon' => '🧠',
                'color' => '#DAA520',
                'display_order' => 8,
            ],
            [
                'name' => 'Romance',
                'slug' => 'romance',
                'description' => 'Romance novels and love stories',
                'icon' => '💕',
                'color' => '#D2691E',
                'display_order' => 9,
            ],
            [
                'name' => 'Mystery & Thriller',
                'slug' => 'mystery-thriller',
                'description' => 'Mystery and thriller novels',
                'icon' => '🔍',
                'color' => '#8B4513',
                'display_order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
