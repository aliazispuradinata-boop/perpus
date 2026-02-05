@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('extra-css')
    @vite(['resources/css/pages/books.css'])
@endsection

@section('content')
<div class="books-header">
    <h1><i class="fas fa-book-open"></i> Katalog Buku</h1>
    <p>Jelajahi koleksi lengkap buku kami</p>
</div>

<!-- Search and Filter -->
<div class="search-card">
    <form method="GET" action="{{ route('books.index') }}" class="row g-3">
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" placeholder="Cari buku atau penulis..." 
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') == $category->slug)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-select">
                <option value="latest" @selected(request('sort') == 'latest')>Terbaru</option>
                <option value="rating" @selected(request('sort') == 'rating')>Rating Tertinggi</option>
                <option value="title" @selected(request('sort') == 'title')>Judul A-Z</option>
                <option value="author" @selected(request('sort') == 'author')>Penulis A-Z</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>
</div>

<!-- Books Grid -->
@if($books->count() > 0)
    <div class="row">
        @foreach($books as $book)
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <div class="book-card">
                    <div class="book-cover">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}">
                        @else
                            <div class="book-cover-placeholder">
                                <i class="fas fa-book"></i>
                            </div>
                        @endif
                        <div class="book-badge">
                            <i class="fas fa-star"></i> {{ number_format($book->rating, 1) }}
                        </div>
                    </div>
                    <div class="book-info">
                        <h5 class="book-title">{{ Str::limit($book->title, 50) }}</h5>
                        <p class="book-author">{{ Str::limit($book->author, 40) }}</p>
                        
                        <div class="book-rating">
                            <span class="stars">
                                @for($i = 0; $i < floor($book->rating); $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @if($book->rating - floor($book->rating) > 0)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif
                            </span>
                            <span class="rating-value">({{ $book->review_count }})</span>
                        </div>

                        <div style="margin-bottom: 1rem;">
                            @if($book->available_copies > 0)
                                <span class="badge bg-success"><i class="fas fa-check"></i> Tersedia</span>
                            @else
                                <span class="badge bg-danger"><i class="fas fa-times"></i> Tidak Tersedia</span>
                            @endif
                        </div>

                        <div class="book-footer">
                            <a href="{{ route('books.show', $book->slug) }}" class="btn-view-detail">
                                <i class="fas fa-eye"></i> Lihat Detail
                            </a>
                            @auth
                                @if($book->available_copies > 0)
                                            <button type="button" class="btn btn-sm btn-primary" style="width: 100%; margin-top: 0.5rem;" data-bs-toggle="modal" data-bs-target="#borrowModal" data-book-id="{{ $book->id }}" data-book-title="{{ $book->title }}" data-book-author="{{ $book->author }}" data-book-cover="{{ $book->cover_url }}" data-book-publisher="{{ $book->publisher }}" data-book-stock="{{ $book->available_copies }}">
                                        <i class="fas fa-check"></i> Pinjam
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $books->links() }}
    </div>
@else
    <div class="no-books">
        <i class="fas fa-inbox"></i>
        <h3>Tidak ada buku ditemukan</h3>
        <p>Coba ubah filter pencarian Anda</p>
    </div>
@endif

<!-- Borrow Modal -->
@auth
<div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 8px 30px rgba(139, 69, 19, 0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; border: none;">
                <h5 class="modal-title" id="borrowModalLabel">
                    <i class="fas fa-book"></i> Form Peminjaman Buku
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <form id="borrowForm">
                    @csrf
                    <input type="hidden" name="book_id" id="bookId">
                    
                    <!-- Book Info -->
                    <div class="row mb-4" style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.1) 0%, rgba(244, 164, 96, 0.1) 100%); padding: 1.5rem; border-radius: 8px;">
                        <div class="col-md-3">
                            <img id="bookCover" src="" alt="Book Cover" style="width: 100%; border-radius: 8px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <h6 style="color: #8B4513; font-weight: 700; margin-bottom: 0.5rem;" id="bookTitle"></h6>
                            <p style="color: #2C1810; margin-bottom: 0.5rem;">
                                <strong>Penulis:</strong> <span id="bookAuthor"></span>
                            </p>
                            <p style="color: #2C1810; margin-bottom: 0.5rem;">
                                <strong>Penerbit:</strong> <span id="bookPublisher"></span>
                            </p>
                            <p style="color: #2C1810; margin-bottom: 0;">
                                <strong>Stok Tersedia:</strong> <span id="bookStock" class="badge bg-success"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Borrow Date -->
                    <div class="form-group mb-3">
                        <label for="borrowDate" style="font-weight: 600; color: #2C1810;">Tanggal Peminjaman</label>
                        <input type="date" class="form-control" id="borrowDate" name="borrow_date" required style="border: 2px solid #E8D5C4; border-radius: 8px; padding: 0.75rem;">
                    </div>

                    <!-- Duration Days -->
                    <div class="form-group mb-3">
                        <label for="durationDays" style="font-weight: 600; color: #2C1810;">Durasi Peminjaman (Hari)</label>
                        <div class="input-group" style="margin-bottom: 1rem;">
                            <button type="button" class="btn btn-outline-secondary" id="minusBtn" style="border: 2px solid #E8D5C4;">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="form-control text-center" id="durationDays" name="duration_days" min="1" max="30" value="14" readonly style="border: 2px solid #E8D5C4; border-radius: 0; font-weight: 600; font-size: 1.2rem;">
                            <button type="button" class="btn btn-outline-secondary" id="plusBtn" style="border: 2px solid #E8D5C4;">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <small style="color: #2C1810;">Maximum: 30 hari</small>
                    </div>

                    <!-- Due Date -->
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; color: #2C1810;">Harus Dikembalikan Pada</label>
                        <div style="background: #f9f9f9; padding: 1rem; border-radius: 8px; border: 2px solid #E8D5C4;">
                            <h6 id="dueDate" style="color: #8B4513; font-weight: 700; margin: 0;">-</h6>
                        </div>
                        <!-- Hidden input to submit due_date (ISO format) -->
                        <input type="hidden" id="dueDateInput" name="due_date" value="">
                    </div>

                    <!-- Late Fee Info -->
                    <div class="alert alert-warning" style="background: linear-gradient(135deg, rgba(210, 105, 30, 0.15) 0%, rgba(244, 164, 96, 0.15) 100%); border: 2px solid #E8D5C4; border-radius: 8px;">
                        <h6 style="color: #8B4513; font-weight: 700; margin-bottom: 0.5rem;">
                            <i class="fas fa-exclamation-circle"></i> Keterangan Denda
                        </h6>
                        <p style="color: #2C1810; margin: 0; font-size: 0.9rem;">
                            Jika buku tidak dikembalikan tepat waktu, akan dikenakan denda <strong>Rp 5.000 per hari</strong> untuk keterlambatan. Pastikan untuk mengembalikan buku sesuai dengan tanggal yang telah ditentukan.
                        </p>
                    </div>

                    <small style="color: #2C1810; display: block; margin-top: 1rem;">
                        <i class="fas fa-info-circle"></i> Setelah mengklik "Pinjam", Anda akan mendapatkan QR code yang harus ditunjukkan kepada petugas untuk konfirmasi.
                    </small>
                </form>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #E8D5C4; padding: 1.5rem;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="submitBorrowBtn" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); border: none;">
                    <i class="fas fa-check"></i> Pinjam
                </button>
            </div>
        </div>
    </div>
</div>
@endauth

@endsection

@section('extra-js')
    @vite(['resources/js/pages/books.js'])
    <script>
        @auth
        document.addEventListener('DOMContentLoaded', function() {
            const borrowModal = document.getElementById('borrowModal');
            const borrowButtons = document.querySelectorAll('[data-bs-target="#borrowModal"]');
            const durationInput = document.getElementById('durationDays');
            const minusBtn = document.getElementById('minusBtn');
            const plusBtn = document.getElementById('plusBtn');
            const borrowDateInput = document.getElementById('borrowDate');
            const dueDateDisplay = document.getElementById('dueDate');
            const submitBtn = document.getElementById('submitBorrowBtn');
            const borrowForm = document.getElementById('borrowForm');

            // Set today as default borrow date
            const today = new Date().toISOString().split('T')[0];
            borrowDateInput.value = today;
            borrowDateInput.min = today;

            // Update due date display
            function updateDueDate() {
                const borrowDate = new Date(borrowDateInput.value);
                const duration = parseInt(durationInput.value);
                const dueDate = new Date(borrowDate);
                dueDate.setDate(dueDate.getDate() + duration);
                
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                dueDateDisplay.textContent = dueDate.toLocaleDateString('id-ID', options);
                // Set hidden ISO due date value for form submission
                const iso = dueDate.toISOString().split('T')[0];
                const dueDateInputHidden = document.getElementById('dueDateInput');
                if (dueDateInputHidden) {
                    dueDateInputHidden.value = iso;
                }
            }

            // Populate modal when it's shown (uses relatedTarget for correctness)
            const bsBorrowModalEl = document.getElementById('borrowModal');
            bsBorrowModalEl.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;

                const bookId = button.getAttribute('data-book-id');
                const bookTitle = button.getAttribute('data-book-title');
                const bookAuthor = button.getAttribute('data-book-author');
                const bookCover = button.getAttribute('data-book-cover');
                const bookPublisher = button.getAttribute('data-book-publisher');
                const bookStock = button.getAttribute('data-book-stock');

                document.getElementById('bookId').value = bookId;
                document.getElementById('bookTitle').textContent = bookTitle;
                document.getElementById('bookAuthor').textContent = bookAuthor;
                document.getElementById('bookCover').src = bookCover;
                document.getElementById('bookPublisher').textContent = bookPublisher;
                document.getElementById('bookStock').textContent = bookStock;

                durationInput.value = 14;
                // ensure borrowDate has a value
                if (!borrowDateInput.value) borrowDateInput.value = today;
                updateDueDate();
            });

            // Minus button
            minusBtn.addEventListener('click', function() {
                const current = parseInt(durationInput.value);
                if (current > 1) {
                    durationInput.value = current - 1;
                    updateDueDate();
                }
            });

            // Plus button
            plusBtn.addEventListener('click', function() {
                const current = parseInt(durationInput.value);
                if (current < 30) {
                    durationInput.value = current + 1;
                    updateDueDate();
                }
            });

            // Borrow date change
            borrowDateInput.addEventListener('change', updateDueDate);

            // Duration input change
            durationInput.addEventListener('change', updateDueDate);

            // Submit borrow form
            submitBtn.addEventListener('click', function() {
                const bookId = document.getElementById('bookId').value;
                const borrowDate = document.getElementById('borrowDate').value;
                const durationDays = document.getElementById('durationDays').value;

                if (!bookId || !borrowDate || !durationDays) {
                    alert('Silakan isi semua field');
                    return;
                }

                const formData = new FormData();
                formData.append('book_id', bookId);
                formData.append('borrow_date', borrowDate);
                formData.append('duration_days', durationDays);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                fetch('{{ route("borrowings.store") }}', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the borrow modal
                        const modal = bootstrap.Modal.getInstance(borrowModal);
                        modal.hide();

                        // Redirect to proof page if redirect_url is provided
                        if (data.redirect_url) {
                            // Redirect to proof page with waiting confirmation modal
                            window.location.href = data.redirect_url;
                        } else {
                            // Fallback to QR code modal if no redirect_url
                            showQRCodeModal(data.qr_code, data.message);
                        }
                        
                        // Reset form
                        borrowForm.reset();
                        durationInput.value = 14;
                        borrowDateInput.value = today;
                        updateDueDate();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Pinjam';
                });
            });

            function showQRCodeModal(qrCodeUrl, message) {
                const qrModal = document.createElement('div');
                qrModal.innerHTML = `
                    <div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 8px 30px rgba(139, 69, 19, 0.2);">
                                <div class="modal-header" style="background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%); color: white; border: none;">
                                    <h5 class="modal-title">
                                        <i class="fas fa-qrcode"></i> QR Code Peminjaman
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center" style="padding: 2rem;">
                                    <div class="alert alert-success" role="alert">
                                        <i class="fas fa-check-circle"></i> ${message}
                                    </div>
                                    <img src="${qrCodeUrl}" alt="QR Code" style="width: 200px; height: 200px; border-radius: 8px; border: 2px solid #E8D5C4;">
                                    <p style="margin-top: 1rem; color: #2C1810; font-size: 0.9rem;">
                                        <strong>Tunjukkan QR code ini kepada petugas untuk konfirmasi peminjaman.</strong>
                                    </p>
                                </div>
                                <div class="modal-footer" style="border-top: 1px solid #E8D5C4; padding: 1.5rem;">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                        <i class="fas fa-check"></i> OK
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(qrModal);
                const modal = new bootstrap.Modal(document.getElementById('qrModal'));
                modal.show();

                // Cleanup
                document.getElementById('qrModal').addEventListener('hidden.bs.modal', function() {
                    qrModal.remove();
                });
            }
        });
        @endauth
    </script>
@endsection

