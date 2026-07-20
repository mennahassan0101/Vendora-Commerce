<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect(['Dresses', 'Bags', 'Accessories', 'Shoes'])
            ->map(fn ($name) => Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'is_active' => true]
            ));

        $products = [
            ['name' => 'Rosalie Wrap Dress',    'price' => 89.00,  'compare_price' => 109.00, 'stock' => 14, 'featured' => true],
            ['name' => 'Blush Tote Bag',        'price' => 64.00,  'compare_price' => null,   'stock' => 8,  'featured' => true],
            ['name' => 'Petal Drop Earrings',   'price' => 28.00,  'compare_price' => null,   'stock' => 25, 'featured' => false],
            ['name' => 'Rose Satin Heels',      'price' => 112.00, 'compare_price' => 140.00, 'stock' => 3,  'featured' => true],
            ['name' => 'Everyday Midi Skirt',   'price' => 54.00,  'compare_price' => null,   'stock' => 0,  'featured' => false],
            ['name' => 'Soft Blush Scarf',      'price' => 32.00,  'compare_price' => null,   'stock' => 19, 'featured' => false],
        ];

        foreach ($products as $data) {
            $slug = Str::slug($data['name']);

            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'description' => "A closer look at the {$data['name']} — thoughtfully made, true to size, and easy to style.",
                    'short_description' => 'A versatile piece designed to be worn on repeat.',
                    'sku' => strtoupper(Str::random(8)),
                    'price' => $data['price'],
                    'compare_price' => $data['compare_price'],
                    'stock' => $data['stock'],
                    'is_active' => true,
                    'is_featured' => $data['featured'],
                ]
            );

            $product->categories()->syncWithoutDetaching([$categories->random()->id]);

            // Image generation temporarily disabled while GD is investigated.
            // Products will just render "No image yet" placeholders in the UI.
        }
    }

    /**
     * Generates a simple on-brand placeholder image with GD so you have
     * something real to look at without needing actual product photos yet.
     * Swap these out for real uploads later via the admin dashboard.
     */
    private function generatePlaceholderImage(string $name, string $slug): string
    {
        $width = 600;
        $height = 600;

        $image = imagecreatetruecolor($width, $height);

        $blush = imagecolorallocate($image, 255, 246, 248); // #fff6f8
        $rose  = imagecolorallocate($image, 236, 89, 128);  // #ec5980

        imagefill($image, 0, 0, $blush);
        imagefilledellipse($image, (int) ($width / 2), (int) ($height / 2), 360, 360, $rose);

        $font = 5;
        $lines = explode("\n", wordwrap($name, 18, "\n", true));
        $lineHeight = imagefontheight($font) + 6;
        $startY = ($height / 2) - (count($lines) * $lineHeight / 2);

        foreach ($lines as $i => $line) {
            $textWidth = imagefontwidth($font) * strlen($line);
            $x = (int) (($width - $textWidth) / 2);
            $y = (int) ($startY + $i * $lineHeight);
            imagestring($image, $font, $x, $y, $line, $blush);
        }

        $relativePath = "products/{$slug}.png";
        imagepng($image, storage_path("app/public/{$relativePath}"));
        imagedestroy($image);

        return $relativePath;
    }
}