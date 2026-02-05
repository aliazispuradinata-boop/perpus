@component('mail::message')
# Ulasan Baru untuk Buku

Halo Admin,

Pengguna **{{ $user->name }}** baru saja memberikan ulasan untuk buku **{{ $book->title }}**.

## Informasi Ulasan

- **Buku:** {{ $book->title }}
- **Penulis:** {{ $book->author }}
- **Pengguna:** {{ $user->name }} ({{ $user->email }})
- **Rating:** {{ str_repeat('⭐', $review->rating) }} ({{ $review->rating }}/5)
- **Judul Ulasan:** {{ $review->title ?? '-' }}

## Konten Ulasan

{{ $review->content }}

## Status

Ulasan ini sedang menunggu **moderasi** sebelum dipublikasikan ke pengunjung.

@component('mail::button', ['url' => route('admin.reviews.pending')])
Lihat Ulasan di Admin Panel
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
