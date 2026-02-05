<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Peminjaman Buku</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2C1810;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #8B4513;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #D2691E;
            font-size: 14px;
            font-style: italic;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #8B4513;
            text-align: center;
            margin: 30px 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: white;
            background-color: #8B4513;
            padding: 8px 12px;
            margin-bottom: 12px;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            margin-bottom: 12px;
        }
        .info-label {
            width: 150px;
            font-weight: 600;
            color: #8B4513;
        }
        .info-value {
            flex: 1;
            color: #2C1810;
        }
        .divider {
            border: 0;
            border-top: 1px solid #D2691E;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-active {
            background-color: #28a745;
            color: white;
        }
        .status-pending {
            background-color: #ffc107;
            color: #333;
        }
        .status-returned {
            background-color: #6c757d;
            color: white;
        }
        .status-overdue {
            background-color: #dc3545;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #D2691E;
            font-size: 12px;
            color: #666;
        }
        .qr-section {
            text-align: center;
            margin: 30px 0;
        }
        .qr-code {
            max-width: 200px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .highlight {
            background-color: #FFF8DC;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">📚 RetroLib</div>
            <div class="subtitle">Perpustakaan Digital Retro-Modern</div>
        </div>

        <!-- Title -->
        <div class="title">Bukti Peminjaman Buku</div>

        <!-- Borrowing Information -->
        <div class="section">
            <div class="section-title">📋 INFORMASI PEMINJAMAN</div>
            <div class="info-row">
                <div class="info-label">No. Peminjaman:</div>
                <div class="info-value">#{{ str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <span class="status-badge 
                        @if($borrowing->status === 'active') status-active
                        @elseif($borrowing->status === 'pending') status-pending
                        @elseif($borrowing->status === 'returned') status-returned
                        @elseif($borrowing->status === 'overdue') status-overdue
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Pinjam:</div>
                <div class="info-value">{{ optional($borrowing->borrowed_at)->format('d F Y') ?? 'Belum diambil' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Kembali:</div>
                <div class="info-value">{{ optional($borrowing->due_date)->format('d F Y') ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Durasi:</div>
                <div class="info-value">{{ $borrowing->duration_days ?? '-' }} hari</div>
            </div>
        </div>

        <hr class="divider">

        <!-- Borrower Information -->
        <div class="section">
            <div class="section-title">👤 INFORMASI PEMINJAM</div>
            <div class="info-row">
                <div class="info-label">Nama:</div>
                <div class="info-value">{{ $borrowing->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $borrowing->user->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Telepon:</div>
                <div class="info-value">{{ $borrowing->user->phone ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Alamat:</div>
                <div class="info-value">{{ $borrowing->user->address ?? '-' }}</div>
            </div>
        </div>

        <hr class="divider">

        <!-- Book Information -->
        <div class="section">
            <div class="section-title">📖 INFORMASI BUKU</div>
            <div class="info-row">
                <div class="info-label">Judul:</div>
                <div class="info-value">{{ $borrowing->book->title }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Penulis:</div>
                <div class="info-value">{{ $borrowing->book->author }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Penerbit:</div>
                <div class="info-value">{{ $borrowing->book->publisher ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kategori:</div>
                <div class="info-value">{{ $borrowing->book->category->name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">ISBN:</div>
                <div class="info-value">{{ $borrowing->book->isbn ?? '-' }}</div>
            </div>
        </div>

        <hr class="divider">

        <!-- Notes (if any) -->
        @if($borrowing->notes)
        <div class="section">
            <div class="section-title">📝 CATATAN</div>
            <div class="info-value">{{ $borrowing->notes }}</div>
        </div>
        
        <hr class="divider">
        @endif

        <!-- QR Code (if available) -->
        @if($borrowing->qr_code)
        <div class="qr-section">
            <div class="section-title" style="display: inline-block;">📱 KODE QR</div>
            <div class="qr-code">
                {!! $borrowing->qr_code !!}
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak pada {{ now()->format('d F Y H:i:s') }}</p>
            <p>Perpustakaan RetroLib © 2026 - Semua Hak Dilindungi</p>
            <p style="margin-top: 10px; font-size: 10px;">Harap jaga baik-baik dokumen ini sebagai bukti peminjaman Anda.</p>
        </div>
    </div>
</body>
</html>
