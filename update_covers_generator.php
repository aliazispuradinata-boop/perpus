<?php
// update_covers_generator.php
// Scans storage/app/public/covers and produces SQL statements to set books.cover_image
// Usage: php update_covers_generator.php

$baseDir = __DIR__;
$coversDir = $baseDir . '/storage/app/public/covers';
$outputFile = $baseDir . '/generated_update_covers.sql';

if (!is_dir($coversDir)) {
    echo "Covers directory not found: $coversDir\n";
    exit(1);
}

// load .env minimal parser for DB credentials
$envFile = $baseDir . '/.env';
$db = ['DB_HOST' => '127.0.0.1', 'DB_PORT' => '3306', 'DB_DATABASE' => '', 'DB_USERNAME' => 'root', 'DB_PASSWORD' => ''];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (!str_contains($line,'=')) continue;
        [$k,$v] = explode('=', $line, 2);
        $k = trim($k); $v = trim($v);
        if (array_key_exists($k, $db)) $db[$k] = trim($v, "\"'");
    }
}

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $db['DB_HOST'], $db['DB_PORT'], $db['DB_DATABASE']);
try {
    $pdo = new PDO($dsn, $db['DB_USERNAME'], $db['DB_PASSWORD'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    echo "Could not connect to database: " . $e->getMessage() . "\n";
    echo "Please update .env or edit this script with DB credentials.\n";
    exit(1);
}

// fetch books once and build normalized map
$stmt = $pdo->query('SELECT id, title FROM books');
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

function normalize_str($s) {
    $s = mb_strtolower($s, 'UTF-8');
    $s = preg_replace('/\.[^.]+$/','',$s); // remove extension
    $s = preg_replace('/[^a-z0-9]/u','', $s);
    return $s;
}

$bookMap = [];
foreach ($books as $b) {
    $norm = normalize_str($b['title']);
    if (!isset($bookMap[$norm])) $bookMap[$norm] = [];
    $bookMap[$norm][] = $b;
}

$files = array_values(array_filter(scandir($coversDir), function($f){
    return preg_match('/\.(jpe?g|png|webp)$/i', $f);
}));

$updates = [];
$unmatched = [];
$fuzzy = [];

foreach ($files as $file) {
    $normFile = normalize_str($file);
    if (isset($bookMap[$normFile]) && count($bookMap[$normFile]) === 1) {
        $b = $bookMap[$normFile][0];
        $coverPath = 'covers/' . $file; // storage disk path
        $sql = sprintf("UPDATE books SET cover_image = '%s' WHERE id = %d;", addslashes($coverPath), $b['id']);
        $updates[] = $sql;
        echo "Matched exact: {$file} -> {$b['title']}\n";
        continue;
    }

    // fuzzy search: try to find books where normalized title contains file or vice versa
    $candidates = [];
    foreach ($bookMap as $normTitle => $arr) {
        if (strpos($normTitle, $normFile) !== false || strpos($normFile, $normTitle) !== false) {
            foreach ($arr as $b) $candidates[] = $b;
        }
    }

    $candidates = array_unique($candidates, SORT_REGULAR);
    if (count($candidates) === 1) {
        $b = $candidates[0];
        $coverPath = 'covers/' . $file;
        $sql = sprintf("UPDATE books SET cover_image = '%s' WHERE id = %d;", addslashes($coverPath), $b['id']);
        $updates[] = $sql;
        $fuzzy[] = "Fuzzy matched: {$file} -> {$b['title']}";
        echo "Fuzzy matched: {$file} -> {$b['title']}\n";
    } else {
        $unmatched[] = $file;
        echo "Unmatched: {$file}\n";
    }
}

// write SQL file
file_put_contents($outputFile, "-- Generated SQL to update book cover_image\n-- Review before executing\n\n" . implode("\n", $updates) . "\n");

echo "\nGenerated SQL saved to: {$outputFile}\n";
if (count($unmatched) > 0) {
    echo "\nUnmatched files (manual review needed):\n" . implode("\n", $unmatched) . "\n";
}

if (count($fuzzy) > 0) {
    echo "\nFuzzy matches (please verify):\n" . implode("\n", $fuzzy) . "\n";
}

echo "\nNext steps:\n1) Review '{$outputFile}'.\n2) Run the SQL in phpMyAdmin or via MySQL CLI: mysql -u root perpus < generated_update_covers.sql\n3) Run 'php artisan storage:link' if not yet run, or copy covers to public/storage/covers.\n";
