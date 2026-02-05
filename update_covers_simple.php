<?php
// Simple database update script
$host = '127.0.0.1';
$db = 'perpus';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $updates = [
        ['The Great Gatsby', 'storage/covers/the great gatsby.jpeg'],
        ['To Kill a Mockingbird', 'storage/covers/to kill a mockingbird.jpeg'],
        ['Pride and Prejudice', 'storage/covers/prideandprjudice.jpeg'],
        ['The 7 Habits of Highly Effective People', 'storage/covers/the 7 habits of highly effevctive people.jpeg'],
    ];
    
    foreach ($updates as [$title, $path]) {
        $stmt = $pdo->prepare("UPDATE books SET cover_image = ? WHERE title = ?");
        $stmt->execute([$path, $title]);
        echo "✓ Updated: $title\n";
    }
    
    echo "\n✓ All covers updated successfully!\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
