<?php
/**
 * Setup Cover Images - Web Script
 * Akses via: http://localhost:8000/setup-covers.php
 */

$host = '127.0.0.1';
$db = 'perpus';
$user = 'root';
$pass = '';

$results = [];
$error = null;

// Step 1: Update Database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $updates = [
        ['The Great Gatsby', 'covers/the great gatsby.jpeg'],
        ['To Kill a Mockingbird', 'covers/to kill a mockingbird.jpeg'],
        ['Pride and Prejudice', 'covers/prideandprjudice.jpeg'],
        ['The 7 Habits of Highly Effective People', 'covers/the 7 habits of highly effevctive people.jpeg'],
    ];
    
    foreach ($updates as [$title, $path]) {
        $stmt = $pdo->prepare("UPDATE books SET cover_image = ? WHERE title = ?");
        $stmt->execute([$path, $title]);
        $results[] = "✓ Updated: $title";
    }
    
    // Verify
    $stmt = $pdo->query("SELECT id, title, cover_image FROM books WHERE cover_image IS NOT NULL");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = "Database Error: " . $e->getMessage();
}

// Step 2: Create Storage Symlink
$symlink_created = false;
$symlink_msg = '';

$public_storage = __DIR__ . '/public/storage';
$app_public = __DIR__ . '/storage/app/public';

if (!is_dir($public_storage) && is_dir($app_public)) {
    // Try creating symlink
    $system_result = @symlink($app_public, $public_storage);
    if ($system_result) {
        $symlink_created = true;
        $symlink_msg = "✓ Symlink created successfully";
    } else {
        // Try Windows mklink alternative
        exec("cmd /c mklink /D \"$public_storage\" \"$app_public\" 2>&1", $output, $return_code);
        if ($return_code === 0) {
            $symlink_created = true;
            $symlink_msg = "✓ Symlink created via mklink";
        } else {
            // Fallback: copy directory
            if (!file_exists($public_storage)) {
                mkdir($public_storage, 0755, true);
                $files = scandir($app_public);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        copy("$app_public/$file", "$public_storage/$file");
                    }
                }
                $symlink_msg = "✓ Files copied to public/storage (symlink failed but files accessible)";
            }
        }
    }
} elseif (is_dir($public_storage)) {
    $symlink_msg = "✓ Storage symlink already exists";
    $symlink_created = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Cover Images</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2rem;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
        .step {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .step h2 {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .step-icon {
            font-size: 1.5rem;
        }
        .result-list {
            list-style: none;
            margin: 10px 0;
        }
        .result-list li {
            color: #27ae60;
            padding: 8px 0;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .result-list li:before {
            content: "✓";
            font-weight: bold;
            color: #27ae60;
        }
        .error {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #c33;
            margin-bottom: 20px;
        }
        .success {
            background: #efe;
            color: #3c3;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #3c3;
            margin-bottom: 20px;
        }
        .table-container {
            overflow-x: auto;
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9rem;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 15px;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📸 Setup Cover Images</h1>
        <p class="subtitle">Konfigurasi otomatis untuk menampilkan cover buku di website</p>

        <?php if ($error): ?>
            <div class="error">
                <strong>❌ Error:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php else: ?>
            <!-- Step 1: Database Update -->
            <div class="step">
                <h2><span class="step-icon">1️⃣</span> Update Database</h2>
                <p style="color: #666; margin-bottom: 15px;">Menghubungkan 4 buku dengan file cover yang ada:</p>
                <ul class="result-list">
                    <?php foreach ($results as $result): ?>
                        <li><?= htmlspecialchars($result) ?></li>
                    <?php endforeach; ?>
                </ul>

                <?php if (!empty($rows)): ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Judul Buku</th>
                                    <th>Cover Path</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['title']) ?></td>
                                        <td><code style="background: #f0f0f0; padding: 2px 6px; border-radius: 3px;"><?= htmlspecialchars($row['cover_image']) ?></code></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="success" style="margin-top: 15px;">
                        ✓ Database berhasil diupdate! (<?= count($rows) ?> buku dengan cover)
                    </div>
                <?php endif; ?>
            </div>

            <!-- Step 2: Storage Symlink -->
            <div class="step">
                <h2><span class="step-icon">🔗</span> Setup Storage Access</h2>
                <p style="color: #666; margin-bottom: 15px;">Membuat akses file cover dari folder storage:</p>
                <ul class="result-list">
                    <li><?= htmlspecialchars($symlink_msg) ?></li>
                </ul>
                <?php if ($symlink_created): ?>
                    <div class="success" style="margin-top: 15px;">
                        ✓ Storage berhasil dikonfigurasi!
                    </div>
                <?php endif; ?>
            </div>

            <!-- Step 3: Verifikasi -->
            <div class="step">
                <h2><span class="step-icon">✅</span> Verifikasi</h2>
                <p style="color: #666; margin-bottom: 15px;">Langkah selanjutnya:</p>
                <ol style="color: #666; padding-left: 20px; line-height: 1.8;">
                    <li><strong>Refresh browser</strong> untuk clear cache</li>
                    <li>Buka <strong>Landing Page</strong> → cek apakah cover tampil di "Buku Pilihan"</li>
                    <li>Buka <strong>Katalog Buku</strong> → lihat cover di setiap card</li>
                    <li>Buka <strong>Detail Buku</strong> → lihat cover besar di samping</li>
                    <li>Admin → <strong>Kelola Buku</strong> → lihat preview cover di edit form</li>
                </ol>
                <button class="btn" onclick="window.location.href='/'">Pergi ke Landing Page</button>
            </div>

            <div class="footer">
                <p>✨ Setup selesai! Jika masih ada error, cek console browser atau storage/logs/laravel.log</p>
                <p style="margin-top: 10px;">File ini bisa dihapus setelah selesai: <code style="background: #f0f0f0; padding: 2px 6px;">setup-covers.php</code></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
