@extends('layouts.app')

@push('styles')
<style>
    /* Mobile responsive improvements for Borrowing Requests */
    @media (max-width: 768px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .card-body {
            padding: 1rem 0.75rem;
        }
        
        .card-header {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        h4 {
            font-size: 1.25rem;
        }
        
        .table-responsive {
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .table {
            font-size: 0.875rem;
        }
        
        .table th, .table td {
            padding: 0.5rem;
            vertical-align: middle;
        }
        
        .badge {
            font-size: 0.625rem;
            padding: 0.25rem 0.5rem;
        }
        
        .btn {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.375rem 0.5rem;
        }
        
        /* Mobile table improvements */
        .hide-on-mobile {
            display: none;
        }
        
        /* Mobile timer adjustments */
        .timer-container {
            font-size: 0.875rem;
        }
        
        .countdown {
            font-size: 0.875rem;
        }
        
        .progress {
            height: 3px;
        }
        
        /* Mobile empty state */
        .empty-state {
            padding: 2rem 1rem;
        }
        
        .empty-state h5 {
            font-size: 1.25rem;
        }
        
        .empty-state p {
            font-size: 0.875rem;
        }
        
        /* Mobile modal adjustments */
        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        .modal-header {
            padding: 1rem;
        }
        
        .modal-footer {
            padding: 1rem;
        }
        
        /* Mobile pagination */
        .pagination {
            justify-content: center;
            margin-top: 1rem;
        }
        
        .pagination .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .card-header {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        
        h4 {
            font-size: 1.125rem;
        }
        
        .table {
            font-size: 0.8rem;
        }
        
        .table th, .table td {
            padding: 0.375rem;
        }
        
        .btn {
            font-size: 0.8125rem;
            padding: 0.375rem 0.625rem;
        }
        
        .btn-sm {
            font-size: 0.75rem;
            padding: 0.25rem 0.375rem;
        }
        
        .badge {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
        
        .timer-container {
            font-size: 0.8rem;
        }
        
        .countdown {
            font-size: 0.8rem;
        }
    }

    /* Touch-friendly interactions */
    @media (hover: none) and (pointer: coarse) {
        .btn:active {
            transform: scale(0.98);
        }
        
        .table tr:active {
            background-color: #f8f9fa;
        }
        
        .card:active {
            transform: scale(0.99);
        }
    }

    /* Ensure proper scrolling */
    * {
        -webkit-overflow-scrolling: touch;
    }

    /* Mobile-specific table layout */
    @media (max-width: 480px) {
        .table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .table thead,
        .table tbody,
        .table th,
        .table td,
        .table tr {
            display: table-cell;
            float: none;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">My Borrowing Requests</h4>
                </div>

                <div class="card-body">
                    @if($borrowingRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Book</th>
                                        <th class="hide-on-mobile">Author</th>
                                        <th class="hide-on-mobile">Category</th>
                                        <th>Requested</th>
                                        <th>Status</th>
                                        <th>Time Left</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowingRequests as $request)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $request->book->title }}</div>
                                                <small class="text-muted hide-on-mobile">{{ $request->book->author->name ?? 'Unknown' }}</small>
                                            </td>
                                            <td class="hide-on-mobile">{{ $request->book->author->name ?? 'Unknown' }}</td>
                                            <td class="hide-on-mobile">{{ $request->book->category->name ?? 'Unknown' }}</td>
                                            <td>
                                                <div>{{ $request->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $request->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $request->status === 'approved' ? 'success' : 
                                                    ($request->status === 'pending' ? 'warning' : 
                                                    ($request->status === 'expired' ? 'secondary' : 
                                                    ($request->status === 'cancelled' ? 'info' : 'danger'))) 
                                                }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($request->status === 'pending' && $request->expires_at)
                                                    <div class="timer-container" data-expires="{{ $request->expires_at->toISOString() }}">
                                                        <span class="countdown text-warning fw-bold">Loading...</span>
                                                        <div class="progress mt-1" style="height: 4px;">
                                                            <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" 
                                                                 role="progressbar" style="width: 100%"></div>
                                                        </div>
                                                    </div>
                                                @elseif($request->status === 'expired')
                                                    <span class="text-muted">Expired</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->status === 'approved')
                                                    <a href="{{ route('books.show', $request->book->id) }}" class="btn btn-sm btn-primary">
                                                        View Book
                                                    </a>
                                                @elseif($request->status === 'pending')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger cancel-simple-btn" 
                                                            data-request-id="{{ $request->id }}"
                                                            title="Cancel this borrowing request">
                                                        Cancel
                                                    </button>
                                                @elseif(in_array($request->status, ['cancelled', 'rejected', 'expired']))
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-request-btn" 
                                                            data-request-id="{{ $request->id }}"
                                                            data-book-title="{{ $request->book->title }}"
                                                            title="Delete this borrowing request">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $borrowingRequests->links() }}
                        </div>
                    @else
                        <div class="text-center py-4 empty-state">
                            <i class="bi bi-clock-history display-1 text-muted"></i>
                            <h5 class="mt-3">No borrowing requests found</h5>
                            <p class="text-muted">You haven't made any borrowing requests yet.</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary">
                                Browse Books
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Request Confirmation Modal -->
<div class="modal fade" id="cancelRequestModal" tabindex="-1" aria-labelledby="cancelRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelRequestModalLabel">Cancel Borrowing Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel your borrowing request for:</p>
                <p class="fw-bold text-primary" id="cancelBookTitle"></p>
                <p class="text-muted small">The book will become available for other users to request.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Request</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Cancel Request</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Request Confirmation Modal -->
<div class="modal fade" id="deleteRequestModal" tabindex="-1" aria-labelledby="deleteRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRequestModalLabel">Delete Borrowing Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete your borrowing request for:</p>
                <p class="fw-bold text-danger" id="deleteBookTitle"></p>
                <p class="text-muted small"><strong>This action cannot be undone.</strong> The request will be permanently removed from your history.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Request</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash me-2"></i>Delete Request
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const timers = document.querySelectorAll('.timer-container');
    
    timers.forEach(timer => {
        const expiresAt = new Date(timer.dataset.expires);
        const countdownElement = timer.querySelector('.countdown');
        const progressBar = timer.querySelector('.progress-bar');
        
        // Calculate total duration (2 hours = 7200000 ms)
        const totalDuration = 2 * 60 * 60 * 1000;
        
        function updateCountdown() {
            const now = new Date();
            const timeLeft = expiresAt - now;
            
            if (timeLeft <= 0) {
                countdownElement.textContent = 'Expired';
                countdownElement.className = 'countdown text-muted fw-bold';
                progressBar.style.width = '0%';
                progressBar.className = 'progress-bar bg-secondary';
                return;
            }
            
            const hours = Math.floor(timeLeft / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            let timeString = '';
            if (hours > 0) {
                timeString = `${hours}h ${minutes}m ${seconds}s`;
            } else if (minutes > 0) {
                timeString = `${minutes}m ${seconds}s`;
            } else {
                timeString = `${seconds}s`;
            }
            
            countdownElement.textContent = timeString;
            
            // Update progress bar
            const elapsed = totalDuration - timeLeft;
            const progress = (elapsed / totalDuration) * 100;
            progressBar.style.width = progress + '%';
            
            // Change color as time runs out
            if (timeLeft < 5 * 60 * 1000) { // Less than 5 minutes
                countdownElement.className = 'countdown text-danger fw-bold';
                progressBar.className = 'progress-bar bg-danger progress-bar-striped progress-bar-animated';
            } else if (timeLeft < 30 * 60 * 1000) { // Less than 30 minutes
                countdownElement.className = 'countdown text-warning fw-bold';
                progressBar.className = 'progress-bar bg-warning progress-bar-striped progress-bar-animated';
            } else {
                countdownElement.className = 'countdown text-success fw-bold';
                progressBar.className = 'progress-bar bg-success';
            }
        }
        
        // Update immediately
        updateCountdown();
        
        // Update every second
        setInterval(updateCountdown, 1000);
    });

    // Cancel Request functionality
    let currentRequestId = null;
    let cancelModal = null;
    
    // Initialize modal safely
    try {
        const cancelModalElement = document.getElementById('cancelRequestModal');
        if (cancelModalElement) {
            cancelModal = new bootstrap.Modal(cancelModalElement);
        }
    } catch (error) {
        console.error('Error initializing modal:', error);
    }
    
    // Handle X button clicks (quick cancel)
    document.querySelectorAll('.cancel-simple-btn').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.dataset.requestId;
            const bookTitle = this.closest('tr').querySelector('td:first-child').textContent;
            
            if (confirm(`Cancel request for "${bookTitle}"?`)) {
                cancelRequestDirectly(requestId);
            }
        });
    });
    
    // Handle delete button clicks
    document.querySelectorAll('.delete-request-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const requestId = this.dataset.requestId;
            const bookTitle = this.dataset.bookTitle;
            
            // Simple confirmation
            if (confirm(`Permanently delete request for "${bookTitle}"? This action cannot be undone.`)) {
                deleteRequestDirectly(requestId);
            }
        });
    });
    
    // Function to handle cancel request directly
    function cancelRequestDirectly(requestId) {
        const confirmBtn = document.getElementById('confirmCancelBtn');
        if (confirmBtn) {
            // Show loading state
            confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Cancelling...';
            confirmBtn.disabled = true;
        }
        
        // Send cancel request
        fetch(`/borrowing-requests/${requestId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide modal if it exists
                if (cancelModal) {
                    cancelModal.hide();
                }
                
                // Show success message
                showAlert(data.message, 'success');
                
                // Reload page after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while cancelling your request. Please try again.', 'danger');
        })
        .finally(() => {
            // Reset button state
            if (confirmBtn) {
                confirmBtn.innerHTML = 'Cancel Request';
                confirmBtn.disabled = false;
            }
            currentRequestId = null;
        });
    }
    
    // Function to handle delete request directly
    function deleteRequestDirectly(requestId) {
        // Send delete request
        fetch(`/borrowing-requests/${requestId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and reload
                showAlert(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while deleting your request. Please try again.', 'danger');
        });
    }
    
    // Function to show alerts
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.prepend(alertDiv);
        }
    }
    
    // Handle confirm cancel button click
    const confirmBtn = document.getElementById('confirmCancelBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (currentRequestId) {
                cancelRequestDirectly(currentRequestId);
            }
        });
    }
});
</script>

@endsection
