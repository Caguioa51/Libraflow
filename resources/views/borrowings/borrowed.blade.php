@extends('layouts.app')

@push('styles')
<style>
    .btn-update-due-date {
        color: #6c757d;
        background: none;
        border: none;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        transition: all 0.2s;
    }
    .btn-update-due-date:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    /* Mobile responsive improvements */
    @media (max-width: 768px) {
        .statistics-cards .col-md-3 {
            margin-bottom: 1rem;
        }
        
        .search-form .col-md-6,
        .search-form .col-md-4,
        .search-form .col-md-2 {
            margin-bottom: 0.75rem;
        }
        
        .table-responsive {
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .borrowed-table {
            font-size: 0.875rem;
        }
        
        .borrowed-table th,
        .borrowed-table td {
            padding: 0.5rem;
            vertical-align: middle;
        }
        
        .book-info {
            max-width: 200px;
        }
        
        .borrower-info {
            max-width: 180px;
        }
        
        .action-buttons .btn-group {
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        
        .pagination-info {
            font-size: 0.875rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        
        .empty-state {
            padding: 2rem 1rem;
        }
        
        .empty-state h4 {
            font-size: 1.25rem;
        }
        
        .empty-state p {
            font-size: 0.875rem;
        }
    }

    @media (max-width: 576px) {
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch !important;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        
        .book-info,
        .borrower-info {
            max-width: 150px;
        }
        
        .badge {
            font-size: 0.625rem;
            padding: 0.25rem 0.375rem;
        }
        
        h1 {
            font-size: 1.5rem;
        }
        
        .card-header {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
    }

    /* Touch-friendly buttons */
    @media (hover: none) and (pointer: coarse) {
        .btn:active {
            transform: scale(0.98);
        }
        
        .btn-update-due-date:active {
            background-color: #e9ecef;
        }
    }

    /* Ensure proper table scrolling */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Hide less important columns on very small screens */
    @media (max-width: 480px) {
        .hide-on-mobile {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Borrowed Books</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('borrowings.report') }}" class="btn btn-outline-secondary">
                <i class="bi bi-bar-chart me-1"></i>Reports
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 statistics-cards">
        <div class="col-md-3 col-sm-6">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary">{{ $totalBorrowed }}</h5>
                    <p class="card-text">Total Borrowed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h5 class="card-title text-danger">{{ $overdueCount }}</h5>
                    <p class="card-text">Overdue</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5 class="card-title text-warning">{{ $dueTodayCount }}</h5>
                    <p class="card-text">Due Today</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="card-title text-success">{{ $totalBorrowed - $overdueCount }}</h5>
                    <p class="card-text">On Time</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('borrowings.borrowed') }}" class="search-form">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by book title or borrower name">
                    </div>
                    <div class="col-md-4">
                        <label for="filter" class="form-label">Filter</label>
                        <select class="form-select" id="filter" name="filter">
                            <option value="">All Borrowed Books</option>
                            <option value="overdue" {{ request('filter') == 'overdue' ? 'selected' : '' }}>Overdue Books</option>
                            <option value="due_today" {{ request('filter') == 'due_today' ? 'selected' : '' }}>Due Today</option>
                            <option value="due_this_week" {{ request('filter') == 'due_this_week' ? 'selected' : '' }}>Due This Week</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label><br>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Borrowed Books Table -->
    <div class="card">
        <div class="card-body">
            @if($borrowings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover borrowed-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Book</th>
                                <th>Borrower</th>
                                <th class="hide-on-mobile">Borrowed At</th>
                                <th>Due Date</th>
                                <th class="hide-on-mobile">Days Left</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $borrowing)
                                <tr>
                                    <td>
                                        <div class="fw-bold book-info">{{ $borrowing->book->title ?? 'Unknown Book' }}</div>
                                        <small class="text-muted">
                                            {{ $borrowing->book->author->name ?? 'Unknown Author' }}
                                            @if($borrowing->book->isbn) • ISBN: {{ $borrowing->book->isbn }} @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center borrower-info">
                                            <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="bi bi-person text-white" style="font-size: 14px;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $borrowing->user->name ?? 'Unknown User' }}</div>
                                                <small class="text-muted">{{ $borrowing->user->email ?? '' }}</small>
                                                @if($borrowing->user->student_id)
                                                    <br><small class="text-muted">ID: {{ $borrowing->user->student_id }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hide-on-mobile">
                                        <div>{{ $borrowing->borrowed_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $borrowing->borrowed_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="{{ $borrowing->due_date->isPast() ? 'text-danger fw-bold' : '' }}">
                                            {{ $borrowing->due_date->format('M d, Y') }}
                                        </div>
                                        <small class="text-muted">{{ $borrowing->due_date->format('h:i A') }}</small>
                                    </td>
                                    <td class="hide-on-mobile">
                                        @php
                                            $daysLeft = (int) now()->diffInDays($borrowing->due_date, false);
                                        @endphp
                                        @if($daysLeft < 0)
                                            <span class="badge bg-danger">{{ abs($daysLeft) }} days overdue</span>
                                        @elseif($daysLeft == 0)
                                            <span class="badge bg-warning">Due today</span>
                                        @elseif($daysLeft <= 3)
                                            <span class="badge bg-warning text-dark">{{ $daysLeft }} days left</span>
                                        @else
                                            <span class="badge bg-success">{{ $daysLeft }} days left</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $borrowing->due_date->isPast() ? 'danger' : 'primary' }}">
                                            {{ $borrowing->due_date->isPast() ? 'Overdue' : 'Borrowed' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group action-buttons" role="group">
                                            <a href="{{ route('borrowings.show', $borrowing) }}" 
                                               class="btn btn-outline-primary btn-sm" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <!-- Mark as Returned -->
                                            <form action="{{ route('borrowings.mark-as-returned', $borrowing) }}" 
                                                  method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        title="Mark as Returned"
                                                        onclick="return confirm('Mark this book as returned?')">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            </form>

                                            <!-- Renew -->
                                            <!--
                                            @if($borrowing->canRenew() && !$borrowing->due_date->isPast())
                                                <form action="{{ route('borrowings.renew', $borrowing) }}" 
                                                      method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning btn-sm" 
                                                            title="Renew Book"
                                                            onclick="return confirm('Renew this book for another period?')">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </button>
                                                </form>
                                            @endif
-->
                                            
                                            @if(auth()->user()->isAdmin())
    <button type="button" 
            class="btn btn-update-due-date" 
            title="Update Due Date"
            data-borrowing-id="{{ $borrowing->id }}"
            data-current-due-date="{{ $borrowing->due_date->format('Y-m-d') }}">
        <i class="bi bi-calendar2-plus"></i>
    </button>
@endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                    <div class="pagination-info text-muted">
                        Showing {{ $borrowings->firstItem() }} to {{ $borrowings->lastItem() }} of {{ $borrowings->total() }} entries
                    </div>
                    {{ $borrowings->links() }}
                </div>
            @else
                <div class="text-center py-5 empty-state">
                    <i class="bi bi-book-half display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">No Borrowed Books Found</h4>
                    <p class="text-muted">
                        @if(request('search') || request('filter'))
                            No books match your search criteria. 
                            <a href="{{ route('borrowings.borrowed') }}" class="text-decoration-none">Clear filters</a>
                        @else
                            There are currently no borrowed books in the system.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update due date
    function updateDueDate(button) {
        const borrowingId = button.getAttribute('data-borrowing-id');
        const currentDueDate = button.getAttribute('data-current-due-date');
        
        // Format the current due date for display
        const formattedDate = new Date(currentDueDate).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        });
        
        // Set the minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minDate = tomorrow.toISOString().split('T')[0];
        
        // Show prompt for new due date
        const newDate = prompt(`Current due date: ${formattedDate}\n\nEnter new due date (YYYY-MM-DD):`, minDate);
        
        if (!newDate) return; // User cancelled
        
        // Validate date format
        if (!/^\d{4}-\d{2}-\d{2}$/.test(newDate)) {
            alert('Please enter a valid date in YYYY-MM-DD format');
            return;
        }
        
        // Validate future date
        if (new Date(newDate) < tomorrow) {
            alert('Please select a future date');
            return;
        }
        
        // Show loading state
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
        
        // Submit the update with proper JSON handling
        fetch(`/admin/borrowings/${borrowingId}/update-due-date`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                due_date: newDate
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Failed to update due date');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Due date updated successfully!');
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to update due date');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'An error occurred. Please try again.');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }
    
    // Attach click handlers to all update buttons
    document.querySelectorAll('.btn-update-due-date').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            updateDueDate(this);
        });
    });
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateDueDateModal = document.getElementById('updateDueDateModal');
    
    if (!updateDueDateModal) {
        console.error('Update due date modal element not found');
        return;
    }

    // Initialize modal using data-bs-* attributes
    const modal = new bootstrap.Modal(updateDueDateModal, {
        backdrop: 'static',
        keyboard: false
    });

        
    // Handle modal show event
    updateDueDateModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const borrowingId = button.getAttribute('data-borrowing-id');
        const currentDueDate = button.getAttribute('data-current-due-date');
        
        // Update the modal's content
        const currentDueDateSpan = updateDueDateModal.querySelector('#currentDueDate');
        const form = updateDueDateModal.querySelector('form');
        const dueDateInput = updateDueDateModal.querySelector('#due_date');
        
        // Format the current due date for display
        const formattedDate = new Date(currentDueDate).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            weekday: 'long'
        });
        
        currentDueDateSpan.textContent = formattedDate;
        
        // Set the minimum date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        dueDateInput.min = tomorrow.toISOString().split('T')[0];
        
        // Set the form action URL
        form.action = `/admin/borrowings/${borrowingId}/update-due-date`;
        });
        
    // Handle form submission
    const form = document.getElementById('updateDueDateForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            
            // Submit the form via fetch
            fetch(`/admin/borrowings/${borrowingId}/update-due-date`, {
    method: 'POST',  // Changed to POST
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
        due_date: newDate  // Removed _method: 'PUT'
    })
})
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Show success message
                showToast('success', data.message || 'Due date updated successfully');
                
                // Hide the modal
                const modal = bootstrap.Modal.getInstance(updateDueDateModal);
                if (modal) {
                    modal.hide();
                }
                
                // Reload the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                const toastEl = document.getElementById('toast');
                const toastBody = toastEl.querySelector('.toast-body');
                
                toastBody.textContent = error.message || 'Failed to update due date. Please try again.';
                toastEl.classList.remove('bg-success');
                toastEl.classList.add('bg-danger', 'text-white');
                
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
                
                // Re-enable the submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        });
    }
});
</script>
@endpush
