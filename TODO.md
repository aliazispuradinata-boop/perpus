# Task: Add "Tambah Kategori" Button in "Semua Kategori" Dropdown in Kelola Buku

## Description
Add a "+" button next to the category dropdown in the admin books index page (kelola buku) to allow adding new categories directly from the filter dropdown.

## Steps
- [ ] Modify `resources/views/admin/books/index.blade.php` to add "+" button next to category select
- [ ] Add the category modal HTML to the index page
- [ ] Add JavaScript for handling AJAX category creation
- [ ] Test the functionality

## Files to Edit
- `resources/views/admin/books/index.blade.php`

## Dependencies
- Route `admin.categories.store-ajax` already exists
- CategoryController::storeAjax method already exists
- Modal and JS logic can be copied from create.blade.php/edit.blade.php
