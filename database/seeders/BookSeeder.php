<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $bookData = [
            // Fiction
            ['category' => 'fiction', 'title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'isbn' => '978-0-7432-7356-5'],
            ['category' => 'fiction', 'title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'isbn' => '978-0-06-112008-4'],
            ['category' => 'fiction', 'title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'isbn' => '978-0-14-143951-8'],

            // Self-Help
            ['category' => 'self-help', 'title' => 'Atomic Habits', 'author' => 'James Clear', 'isbn' => '978-0-735-21159-6'],
            ['category' => 'self-help', 'title' => 'The 7 Habits of Highly Effective People', 'author' => 'Stephen R. Covey', 'isbn' => '978-0-6714-9119-8'],

            // Business
            ['category' => 'business', 'title' => 'Zero to One', 'author' => 'Peter Thiel', 'isbn' => '978-0-5533-8525-4'],
            ['category' => 'business', 'title' => 'Good to Great', 'author' => 'Jim Collins', 'isbn' => '978-0-06-662099-2'],

            // Technology & AI
            ['category' => 'technology-ai', 'title' => 'The Innovators', 'author' => 'Walter Isaacson', 'isbn' => '978-0-3994-1876-9'],
            ['category' => 'technology-ai', 'title' => 'Clean Code', 'author' => 'Robert C. Martin', 'isbn' => '978-0-1366-0888-1'],

            // Fantasy & Sci-Fi
            ['category' => 'fantasy-sci-fi', 'title' => 'The Lord of the Rings', 'author' => 'J.R.R. Tolkien', 'isbn' => '978-0-5448-0519-2'],
            ['category' => 'fantasy-sci-fi', 'title' => 'Dune', 'author' => 'Frank Herbert', 'isbn' => '978-0-4416-0129-0'],

            // Biography
            ['category' => 'biography', 'title' => 'Steve Jobs', 'author' => 'Walter Isaacson', 'isbn' => '978-1-4516-4853-9'],
            ['category' => 'biography', 'title' => 'Becoming', 'author' => 'Michelle Obama', 'isbn' => '978-1-5247-6313-8'],

            // Health & Wellness
            ['category' => 'health-wellness', 'title' => 'Why We Sleep', 'author' => 'Matthew Walker', 'isbn' => '978-0-3927-8780-9'],
            ['category' => 'health-wellness', 'title' => 'The Body Keeps the Score', 'author' => 'Bessel van der Kolk', 'isbn' => '978-0-6709-2594-1'],

            // Psychology
            ['category' => 'psychology', 'title' => 'Thinking, Fast and Slow', 'author' => 'Daniel Kahneman', 'isbn' => '978-0-3740-3357-6'],
            ['category' => 'psychology', 'title' => 'Emotional Intelligence', 'author' => 'Daniel Goleman', 'isbn' => '978-0-5533-8371-7'],

            // Romance
            ['category' => 'romance', 'title' => 'The Notebook', 'author' => 'Nicholas Sparks', 'isbn' => '978-0-4460-7684-8'],
            ['category' => 'romance', 'title' => 'Outlander', 'author' => 'Diana Gabaldon', 'isbn' => '978-0-3854-9370-3'],

            // Mystery & Thriller
            ['category' => 'mystery-thriller', 'title' => 'The Girl with the Dragon Tattoo', 'author' => 'Stieg Larsson', 'isbn' => '978-0-3071-0958-8'],
            ['category' => 'mystery-thriller', 'title' => 'And Then There Were None', 'author' => 'Agatha Christie', 'isbn' => '978-0-0625-3182-2'],
        ];

        foreach ($bookData as $data) {
            $category = Category::where('slug', $data['category'])->first();

            if ($category) {
                Book::create([
                    'category_id' => $category->id,
                    'title' => $data['title'],
                    'slug' => str()->slug($data['title']),
                    'author' => $data['author'],
                    'isbn' => $data['isbn'],
                    'description' => $faker->paragraph(3),
                    'publisher' => $faker->company(),
                    'published_year' => $faker->year(),
                    'pages' => $faker->numberBetween(200, 500),
                    'language' => 'English',
                    'cover_image' => 'https://via.placeholder.com/300x450?text=' . urlencode($data['title']),
                    'total_copies' => $faker->numberBetween(3, 10),
                    'available_copies' => $faker->numberBetween(1, 10),
                    'rating' => $faker->randomFloat(2, 3.5, 5),
                    'review_count' => $faker->numberBetween(5, 100),
                    'content_preview' => $faker->paragraph(2),
                    'is_featured' => $faker->boolean(60),
                    'is_active' => true,
                ]);
            }
        }
    }
}
