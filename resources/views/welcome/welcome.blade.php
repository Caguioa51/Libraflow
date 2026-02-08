@extends('layouts.app')

@section('content')
<div class="content-wrapper">
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">
                <div class="mb-3 mb-md-0">
                    <h1 class="display-5 fw-bold text-primary mb-2">
                        <i class="bi bi-house-door me-3"></i>Welcome to Our Library
                    </h1>
                    <p class="text-muted lead">Empowering students and teachers through reading, discovery, and lifelong learning.</p>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-speedometer2 me-2"></i>Go to Dashboard
                        </a>
                    @else
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement Section -->
    @php
        $announcement = \App\Models\SystemSetting::get('library_announcement', '');
    @endphp
    @if(!empty($announcement))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-start">
                    <i class="bi bi-megaphone-fill me-3 fs-4 flex-shrink-0"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-2">Library Announcement</h5>
                        <p class="mb-0">{{ nl2br(e($announcement)) }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 bg-primary text-white p-5 d-flex align-items-center">
                        <div>
                            <h2 class="display-6 fw-bold mb-3">Dagupan City National High School Library</h2>
                            <p class="lead mb-4">A center of knowledge and learning for our students and faculty.</p>
                            <div class="d-flex gap-3">
                                @auth
                                    <a href="{{ route('books.index') }}" class="btn btn-light btn-lg px-4">
                                        <i class="bi bi-book me-2"></i>Browse Books
                                    </a>
                                @else
                                    
                                    
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="h-100 d-none d-md-block" style="background: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center/cover;"></div>
                        <div class="h-100 d-md-none" style="background: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center/cover; height: 200px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Library Information -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-clock me-2"></i>Opening Hours
                </div>
                <div class="card-body">
                    <pre class="mb-0" style="font-family: inherit; white-space: pre-wrap;">{{ $libraryHours }}</pre>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-geo-alt me-2"></i>Location
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $libraryLocation }}</p>
                    <a href="https://maps.google.com/?q={{ urlencode($libraryLocation) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-map me-1"></i>View on Map
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-telephone me-2"></i>Contact Us
                </div>
                <div class="card-body">
                    <p class="card-text">
                        <a href="mailto:library@dcnhs.edu.ph" class="text-decoration-none">
                            <i class="bi bi-envelope me-2"></i> library@dcnhs.edu.ph
                        </a><br>
                        <a href="tel:(075)123-4567" class="text-decoration-none">
                            <i class="bi bi-phone me-2"></i> (075) 123-4567
                        </a>
                    </p>
                    <a href="mailto:library@dcnhs.edu.ph?subject=Library%20Inquiry" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-chat-left-text me-1"></i>Send Message
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Books -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0">
                    <i class="bi bi-stars me-2"></i>Featured Books
                </h2>
                <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-primary">
                    View All Books <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            
            <p class="text-muted mb-4">{{ $featuredBooksText }}</p>
            
            <div class="row">
                @php
                    $featuredBooks = \App\Models\Book::with(['author', 'category'])
                        ->where('status', 'available')
                        ->orderBy('created_at', 'desc')
                        ->take(4)
                        ->get();
                @endphp
                
                @forelse($featuredBooks as $book)
                <div class="col-md-3 col-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" class="card-img-top" alt="{{ $book->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-book fs-1 text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title text-truncate" title="{{ $book->title }}">{{ $book->title }}</h5>
                            <p class="card-text text-muted small mb-1">
                                <i class="bi bi-person me-1"></i> {{ $book->author->name ?? 'Unknown Author' }}
                            </p>
                            <p class="card-text text-muted small">
                                <i class="bi bi-tag me-1"></i> {{ $book->category->name ?? 'Uncategorized' }}
                            </p>
                            <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-primary w-100">
                                <i class="bi bi-eye me-1"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i> No featured books available at the moment.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="text-center text-md-start">Dagupan City National High School</h5>
                <p class="text-muted mb-0 text-center text-md-start">Providing quality education and resources since 1945</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end gap-3 mb-2">
                    <a href="#" class="text-white"><i class="bi bi-facebook fs-4"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-twitter fs-4"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-instagram fs-4"></i></a>
                </div>
                <p class="text-muted mt-2 mb-0">&copy; {{ date('Y') }} DCNHS Library. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

@push('styles')
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        border-bottom: none;
        font-weight: 600;
    }
    
    /* Mobile-specific improvements */
    @media (max-width: 767.98px) {
        .display-5 {
            font-size: 2rem;
        }
        .lead {
            font-size: 1rem;
        }
        .card-img-top {
            height: 150px !important;
        }
        .btn-lg {
            padding: 0.5rem 1rem;
            font-size: 1rem;
        }
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
    
    /* Tablet improvements */
    @media (min-width: 768px) and (max-width: 991.98px) {
        .display-5 {
            font-size: 2.5rem;
        }
    }
    
    /* Touch-friendly interactions */
    @media (hover: none) and (pointer: coarse) {
        .hover-lift:active {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.15) !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endpush

@endsection
                </div>
            </div>
        </div>

    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
