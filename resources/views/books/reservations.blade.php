@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-bookmark-check me-2"></i>My Book Reservations</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Reservations</li>
                    </ol>
                </nav>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($reservations->count() > 0)
                <div class="row g-4">
                    @foreach($reservations as $reservation)
                        <div class="col-md-6">
                            <div class="card h-100 border-2 border-{{ $reservation->status === 'active' ? 'primary' : ($reservation->status === 'fulfilled' ? 'success' : 'secondary') }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-1">{{ $reservation->book->title }}</h5>
                                            <p class="card-text text-muted mb-2">
                                                by {{ $reservation->book->author->name }} •
                                                {{ $reservation->book->category->name }}
                                            </p>
                                        </div>
                                        <span class="badge bg-{{ $reservation->status === 'active' ? 'primary' : ($reservation->status === 'fulfilled' ? 'success' : 'secondary') }} fs-6">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Reserved on {{ $reservation->reserved_at->format('M d, Y \a\t g:i A') }}
                                        </small>
                                    </div>

                                    @if($reservation->status === 'active')
                                        <div class="d-grid">
                                            <form method="POST" action="{{ route('books.cancel_reservation', $reservation->book) }}" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-x-circle me-1"></i>Cancel Reservation
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($reservation->status === 'fulfilled')
                                        <div class="alert alert-success py-2">
                                            <small><i class="bi bi-check-circle me-1"></i>This reservation has been fulfilled!</small>
                                        </div>
                                    @else
                                        <div class="alert alert-secondary py-2">
                                            <small><i class="bi bi-x-circle me-1"></i>This reservation was cancelled.</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reservations->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bookmark fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted">No reservations found</h4>
                    <p class="text-muted mb-4">You haven't made any book reservations yet.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Browse Books
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
