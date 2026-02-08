<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Petugas\BorrowingController as PetugasBorrowingController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    $books = \App\Models\Book::where('is_active', true)
        ->where('is_featured', true)
        ->orderBy('created_at', 'desc')
        ->take(12)
        ->get();
    
    $categories = \App\Models\Category::where('is_active', true)->get();
    
    return view('landing', compact('books', 'categories'));
})->name('home');

// Helper route untuk update book covers (temporary)
Route::get('/helper/update-book-covers', [HelperController::class, 'updateBookCovers']);

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/edit-password', [ProfileController::class, 'editPassword'])->name('edit-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
});

// Books Routes
Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('books.index');
    Route::get('/{slug}', [BookController::class, 'show'])->name('books.show');
    
    // Authenticated user routes
    Route::middleware('auth')->group(function () {
        Route::post('/{book}/wishlist', [BookController::class, 'addToWishlist'])->name('books.wishlist.add');
        Route::delete('/{book}/wishlist', [BookController::class, 'removeFromWishlist'])->name('books.wishlist.remove');
        Route::post('/{book}/reviews', [BookController::class, 'storeReview'])->name('books.reviews.store');
    });
});

// User Wishlist Routes
Route::middleware('auth')->prefix('wishlist')->name('wishlist.')->group(function () {
    Route::get('/', [BookController::class, 'wishlist'])->name('index');
    Route::post('/borrow-multiple', [BookController::class, 'borrowFromWishlist'])->name('borrow-multiple');
});

// Borrowing Routes
Route::middleware('auth')->prefix('borrowings')->group(function () {
    Route::get('/history', [BorrowingController::class, 'history'])->name('borrowings.history');
    Route::get('/{borrowing}/receipt', [BorrowingController::class, 'receipt'])->name('borrowings.receipt');
    Route::get('/{borrowing}/proof', [BorrowingController::class, 'proof'])->name('borrowings.proof');
    Route::post('/', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::post('/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
    Route::post('/{borrowing}/renew', [BorrowingController::class, 'renew'])->name('borrowings.renew');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Books Management
    Route::resource('books', AdminBookController::class);
    Route::get('books/export/csv', [AdminBookController::class, 'exportCSV'])->name('books.export-csv');
    Route::post('books/generate-cover', [AdminBookController::class, 'generateCover'])->name('books.generate-cover');
    Route::post('books/save-cover', [AdminBookController::class, 'saveCover'])->name('books.save-cover');
    Route::post('books/batch-assign-covers', [AdminBookController::class, 'batchAssignCovers'])->name('books.batch-assign-covers');
    
    // Category Management (AJAX)
    // Category Management - standalone add page
    Route::get('categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::post('categories/store-ajax', [AdminCategoryController::class, 'storeAjax'])->name('categories.store-ajax');
    
    // Borrowings Management
    Route::resource('borrowings', AdminBorrowingController::class);
    Route::post('borrowings/{borrowing}/approve-return', [AdminBorrowingController::class, 'approveReturn'])
        ->name('borrowings.approve-return');
    Route::post('borrowings/{borrowing}/approve-pending', [AdminBorrowingController::class, 'approvePending'])
        ->name('borrowings.approve-pending');
    Route::post('borrowings/{borrowing}/reject-pending', [AdminBorrowingController::class, 'rejectPending'])
        ->name('borrowings.reject-pending');
    Route::get('borrowings/{borrowing}/print-proof', [AdminBorrowingController::class, 'printProof'])
        ->name('borrowings.print-proof');
    Route::get('borrowings/export/csv', [AdminBorrowingController::class, 'exportCSV'])
        ->name('borrowings.export-csv');
    
    // Users Management (simple show route)
    Route::get('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    
    // Petugas (staff) management: CRUD + promote/revoke
    Route::get('petugas', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('petugas.index');
    Route::get('petugas/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])->name('petugas.create');
    Route::post('petugas', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('petugas.store');
    Route::get('petugas/{user}/edit', [\App\Http\Controllers\Admin\StaffController::class, 'edit'])->name('petugas.edit');
    Route::put('petugas/{user}', [\App\Http\Controllers\Admin\StaffController::class, 'update'])->name('petugas.update');
    Route::delete('petugas/{user}', [\App\Http\Controllers\Admin\StaffController::class, 'destroy'])->name('petugas.destroy');
    Route::post('petugas/{user}/promote', [\App\Http\Controllers\Admin\StaffController::class, 'promote'])->name('petugas.promote');
    Route::post('petugas/{user}/revoke', [\App\Http\Controllers\Admin\StaffController::class, 'revoke'])->name('petugas.revoke');
    Route::get('petugas/export/csv', [\App\Http\Controllers\Admin\StaffController::class, 'exportCSV'])->name('petugas.export-csv');

    // Reviews moderation
    Route::get('reviews/pending', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.pending');
    Route::post('reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::post('reviews/bulk-approve', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkApprove'])->name('reviews.bulk-approve');
    Route::post('reviews/bulk-reject', [\App\Http\Controllers\Admin\ReviewController::class, 'bulkReject'])->name('reviews.bulk-reject');
    Route::get('reviews/export/csv', [\App\Http\Controllers\Admin\ReviewController::class, 'exportCSV'])->name('reviews.export-csv');
});

// Petugas Routes
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('dashboard');
    
    // Books Management
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Petugas\BookController::class, 'index'])->name('index');
        Route::get('/{book}', [\App\Http\Controllers\Petugas\BookController::class, 'show'])->name('show');
        Route::get('/export/csv', [\App\Http\Controllers\Petugas\BookController::class, 'exportCSV'])->name('export-csv');
    });
    
    // Borrowings Management
    Route::prefix('borrowings')->name('borrowings.')->group(function () {
        Route::get('/', [PetugasBorrowingController::class, 'index'])->name('index');
        Route::get('/{borrowing}', [PetugasBorrowingController::class, 'show'])->name('show');
        Route::post('/{borrowing}/approve-pending', [PetugasBorrowingController::class, 'approvePending'])->name('approve-pending');
        Route::post('/{borrowing}/reject-pending', [PetugasBorrowingController::class, 'rejectPending'])->name('reject-pending');
        Route::post('/{borrowing}/confirm', [PetugasBorrowingController::class, 'confirm'])->name('confirm');
        Route::post('/{borrowing}/verify-return', [PetugasBorrowingController::class, 'verifyReturn'])->name('verify-return');
        Route::get('/export', [PetugasBorrowingController::class, 'export'])->name('export');
    });
});

