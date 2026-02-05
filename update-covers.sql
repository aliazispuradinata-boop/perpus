-- Update book covers untuk 4 buku yang sudah ada image-nya
UPDATE books SET cover_image = 'storage/covers/the great gatsby.jpeg' WHERE title = 'The Great Gatsby';
UPDATE books SET cover_image = 'storage/covers/to kill a mockingbird.jpeg' WHERE title = 'To Kill a Mockingbird';
UPDATE books SET cover_image = 'storage/covers/prideandprjudice.jpeg' WHERE title = 'Pride and Prejudice';
UPDATE books SET cover_image = 'storage/covers/the 7 habits of highly effevctive people.jpeg' WHERE title = 'The 7 Habits of Highly Effective People';

-- Verify
SELECT id, title, cover_image FROM books WHERE title IN ('The Great Gatsby', 'To Kill a Mockingbird', 'Pride and Prejudice', 'The 7 Habits of Highly Effective People');
