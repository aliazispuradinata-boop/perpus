<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CoverGeneratorService
{
    /**
     * Fetch actual book cover dari Google Books API menggunakan ISBN
     * Jika tidak ada ISBN, coba dari title
     */
    public static function generateCoverFromISBN($isbn, $title = null, $author = null)
    {
        try {
            // Coba fetch dari Google Books API menggunakan ISBN
            if ($isbn) {
                $imageUrl = self::fetchFromGoogleBooksISBN($isbn);
                if ($imageUrl) {
                    return $imageUrl;
                }
            }

            // Fallback ke title
            if ($title) {
                return self::generateCoverFromTitle($title, $author);
            }

            throw new \Exception('ISBN atau Title harus ada');
        } catch (\Exception $e) {
            // Fallback ke placeholder lokal
            return self::generatePlaceholder($title, $author);
        }
    }

    /**
     * Fetch cover dari Google Books API menggunakan ISBN
     */
    private static function fetchFromGoogleBooksISBN($isbn)
    {
        try {
            $response = Http::timeout(10)->get('https://www.googleapis.com/books/v1/volumes', [
                'q' => 'isbn:' . $isbn,
            ]);

            if ($response->successful() && isset($response['items']) && count($response['items']) > 0) {
                $imageLinks = $response['items'][0]['volumeInfo']['imageLinks'] ?? null;
                if ($imageLinks && isset($imageLinks['thumbnail'])) {
                    // Konversi dari thumbnail ke larger image
                    $thumbnail = $imageLinks['thumbnail'];
                    return str_replace('zoom=1', 'zoom=0', $thumbnail);
                }
            }
        } catch (\Exception $e) {
            \Log::debug('Failed to fetch from Google Books: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate cover image dari Unsplash API
     * Jika API fail, gunakan placeholder dengan background warna
     */
    public static function generateCoverFromTitle($title, $author = null)
    {
        try {
            // Coba ambil dari Unsplash API
            return self::generateFromUnsplash($title);
        } catch (\Exception $e) {
            // Fallback ke placeholder lokal
            return self::generatePlaceholder($title, $author);
        }
    }

    /**
     * Generate cover dari Unsplash
     */
    private static function generateFromUnsplash($title)
    {
        $apiKey = config('services.unsplash.access_key');
        
        if (!$apiKey) {
            throw new \Exception('Unsplash API key not configured');
        }

        // Ambil 1-2 keyword utama dari title
        $keywords = self::extractKeywords($title);
        
        $response = Http::withHeaders([
            'Accept-Version' => 'v1',
        ])->get('https://api.unsplash.com/photos/random', [
            'client_id' => $apiKey,
            'query' => $keywords,
            'orientation' => 'portrait',
            'count' => 1,
        ]);

        if ($response->successful() && isset($response[0]['urls']['regular'])) {
            return $response[0]['urls']['regular'];
        }

        throw new \Exception('Failed to fetch from Unsplash');
    }

    /**
     * Generate placeholder cover dengan warna background random
     */
    private static function generatePlaceholder($title, $author = null)
    {
        $colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8',
            '#F7DC6F', '#BB8FCE', '#85C1E2', '#F8B88B', '#A3E4D7',
            '#FF8B94', '#FFB4A2', '#E5989B', '#C4A69D', '#6C6882'
        ];

        $backgroundColor = $colors[array_rand($colors)];
        
        // Generate via placeholder service atau dibuat di lokal
        $urlEncodedTitle = urlencode($title);
        
        // Gunakan placeholder.com atau dicio.in
        return "https://via.placeholder.com/300x400/{str_replace('#', '', $backgroundColor)}/FFFFFF?text=" . $urlEncodedTitle;
    }

    /**
     * Extract keywords dari title
     */
    private static function extractKeywords($title)
    {
        // Remove common words
        $commonWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'dari', 'yang', 'dan', 'atau', 'di', 'ke'];
        
        $words = explode(' ', strtolower($title));
        $keywords = array_filter($words, function($word) use ($commonWords) {
            return !in_array($word, $commonWords) && strlen($word) > 2;
        });

        // Ambil 1-2 keyword pertama
        $keywords = array_slice($keywords, 0, 2);
        
        return implode(' ', $keywords) ?: $title;
    }

    /**
     * Save cover image dari URL ke storage
     */
    public static function saveCoverFromUrl($imageUrl, $bookTitle)
    {
        try {
            $response = Http::timeout(15)->get($imageUrl);
            
            if ($response->successful()) {
                $filename = 'covers/' . Str::slug($bookTitle) . '-' . time() . '.jpg';
                
                \Storage::disk('public')->put($filename, $response->body());
                
                return $filename;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to save cover image: ' . $e->getMessage());
        }

        return null;
    }
}
