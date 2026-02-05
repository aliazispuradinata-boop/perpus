-- ============================================================
-- SQL untuk UPDATE cover_image di Database
-- File: generated_update_covers.sql
-- Tanggal: 2026-02-04
-- ============================================================
-- 
-- INSTRUKSI:
-- 1. Buka phpMyAdmin → pilih database "perpus"
-- 2. Tab SQL → Paste semua kode di bawah
-- 3. Klik "Go" atau "Execute"
-- 4. Refresh halaman website untuk lihat cover
--
-- ============================================================

-- Update 4 buku dengan cover yang sudah ada di folder
UPDATE books SET cover_image = 'covers/the great gatsby.jpeg' WHERE title = 'The Great Gatsby';
UPDATE books SET cover_image = 'covers/to kill a mockingbird.jpeg' WHERE title = 'To Kill a Mockingbird';
UPDATE books SET cover_image = 'covers/prideandprjudice.jpeg' WHERE title = 'Pride and Prejudice';
UPDATE books SET cover_image = 'covers/the 7 habits of highly effevctive people.jpeg' WHERE title = 'The 7 Habits of Highly Effective People';

-- Verifikasi hasil
SELECT id, title, cover_image FROM books WHERE cover_image IS NOT NULL ORDER BY id;
