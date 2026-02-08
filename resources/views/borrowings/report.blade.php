@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center align-items-center mb-4">
                <h2 class="text-center">
                    <i class="fas fa-chart-bar text-primary me-3"></i>
                    Library Inventory & Borrowing Report
                </h2>
            </div>

            <!-- Enhanced Search and Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form method="GET" action="{{ route('borrowings.report') }}" id="filterForm">
                                <div class="row g-3 align-items-end">
                                    <div class="col-lg-4">
                                        <label for="searchInput" class="form-label fw-semibold">
                                            <i class="fas fa-search me-2 text-primary"></i>Search Records
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                            <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                                                   placeholder="Book title or borrower name..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="statusFilter" class="form-label fw-semibold">
                                            <i class="fas fa-filter me-2 text-primary"></i>Status
                                        </label>
                                        <select id="statusFilter" name="status" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Currently Borrowed</option>
                                            <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="fromDate" class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-2 text-primary"></i>From Date
                                        </label>
                                        <input type="date" id="fromDate" name="from_date" class="form-control"
                                               value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="toDate" class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-2 text-primary"></i>To Date
                                        </label>
                                        <input type="date" id="toDate" name="to_date" class="form-control"
                                               value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search me-2"></i>Filter Records
                                            </button>
                                            @if(request()->hasAny(['search', 'status', 'from_date', 'to_date']))
                                                <a href="{{ route('borrowings.report') }}" class="btn btn-outline-secondary">
                                                    <i class="fas fa-times me-1"></i>Clear
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>

                            @if(request()->hasAny(['search', 'status', 'from_date', 'to_date']))
                            <div class="mt-3">
                                <div class="alert alert-info border-0" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>{{ $borrowings->total() }}</strong> borrowing records found matching your criteria.
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h4 class="text-muted">
                        <i class="fas fa-info-circle text-info"></i> Library Summary
                    </h4>
                </div>

                <!-- Total Books Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-book text-primary fs-2 me-3"></i>
                                <div class="text-start flex-grow-1">
                                    <h6 class="card-title mb-1 text-muted">Total Books</h6>
                                    <p class="card-text fs-2 fw-bold mb-0 text-primary d-flex align-items-center" style="min-height: 2.5rem;">{{ $totalBooks }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Books Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card h-100 border-success">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-check-circle text-success fs-2 me-3"></i>
                                <div class="text-start flex-grow-1">
                                    <h6 class="card-title mb-1 text-muted">Available Books</h6>
                                    <p class="card-text fs-2 fw-bold mb-0 text-success d-flex align-items-center" style="min-height: 2.5rem;">{{ $availableBooks }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Currently Borrowed Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card h-100 border-warning">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-user-clock text-warning fs-2 me-3"></i>
                                <div class="text-start flex-grow-1">
                                    <h6 class="card-title mb-1 text-muted">Currently Borrowed</h6>
                                    <p class="card-text fs-2 fw-bold mb-0 text-warning d-flex align-items-center" style="min-height: 2.5rem;">{{ $currentlyBorrowed }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Books Card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
                    <div class="card h-100 border-danger">
                        <div class="card-body text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fas fa-exclamation-triangle text-danger fs-2 me-3"></i>
                                <div class="text-start flex-grow-1">
                                    <h6 class="card-title mb-1 text-muted">Overdue Books</h6>
                                    <p class="card-text fs-2 fw-bold mb-0 text-danger d-flex align-items-center" style="min-height: 2.5rem;">{{ $overdueBooks }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowing Records Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>
                                Borrowing Records
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Book Title</th>
                                            <th>Borrower</th>
                                            <th>Borrowed Date</th>
                                            <th>Returned Date</th>
                                            <th>Status</th>
                                            <th>Due Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($borrowings as $borrowing)
                                        <tr>
                                            <td>
                                                <strong>{{ $borrowing->book->title ?? 'Unknown Book' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $borrowing->book->author->name ?? '' }}</small>
                                            </td>
                                            <td>{{ $borrowing->user->name ?? 'Unknown User' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($borrowing->borrowed_at)->format('M d, Y H:i') }}</td>
                                            <td>{{ $borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at)->format('M d, Y H:i') : '-' }}</td>
                                            <td>
                                                @if($borrowing->status === 'borrowed')
                                                    @php
                                                        $isOverdue = $borrowing->due_date && \Carbon\Carbon::parse($borrowing->due_date)->isPast();
                                                    @endphp
                                                    @if($isOverdue)
                                                        <span class="badge bg-danger">Overdue Books</span>
                                                    @else
                                                        <span class="badge bg-warning">Currently Borrowed</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-success">Returned</span>
                                                @endif
                                            </td>
                                            <td>{{ $borrowing->due_date ? \Carbon\Carbon::parse($borrowing->due_date)->format('M d, Y') : 'Not set' }}</td>
                                            <td>
                                                @if(auth()->user()->isAdmin())
                                                    @if($borrowing->status === 'returned')
                                                        <form method="POST" action="{{ route('borrowings.destroy', $borrowing) }}" class="d-inline" onsubmit="return confirmDelete('{{ addslashes($borrowing->book->title) }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-trash me-1"></i>Delete
                                                            </button>
                                                        </form>
                                                    @else
                                                        @php
                                                            $isOverdue = $borrowing->status === 'borrowed' && $borrowing->due_date && \Carbon\Carbon::parse($borrowing->due_date)->isPast();
                                                        @endphp
                                                        @if($isOverdue || $borrowing->status === 'borrowed')
                                                            <!-- Actions hidden for Currently Borrowed and Overdue Books -->
                                                        @else
                                                            <div class="dropdown">
                                                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Actions</span>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li><form method="POST" action="{{ route('borrowings.renew', $borrowing) }}" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item" onclick="return confirmRenew('{{ addslashes($borrowing->book->title) }}', '{{ $borrowing->due_date->format('M d, Y') }}')">
                                                                            <i class="fas fa-redo text-warning me-2"></i>Renew Book
                                                                        </button>
                                                                    </form></li>
                                                                    <li><form method="POST" action="{{ route('borrowings.mark-as-returned', $borrowing) }}" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item" onclick="return confirmReturn('{{ addslashes($borrowing->book->title) }}', '{{ $borrowing->user->name ?? 'Unknown User' }}')">
                                                                            <i class="fas fa-check-circle text-success me-2"></i>Mark as Returned
                                                                        </button>
                                                                    </form></li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li><form method="POST" action="{{ route('borrowings.destroy', $borrowing) }}" class="d-inline" onsubmit="return confirmDelete('{{ addslashes($borrowing->book->title) }}')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="dropdown-item text-danger">
                                                                            <i class="fas fa-trash me-2"></i>Delete Record
                                                                        </button>
                                                                    </form></li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fs-1 mb-2 text-muted"></i>
                                                <br>
                                                <p class="text-muted">No borrowing records found</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($borrowings->hasPages())
                            <div class="mt-3">
                                {{ $borrowings->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Edit Due Date Modal -->
<div class="modal fade" id="editDueDateModal" tabindex="-1" aria-labelledby="editDueDateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h4 class="modal-title fw-bold" id="editDueDateModalLabel">
                    <i class="fas fa-calendar-edit me-2"></i>📅 Edit Due Date
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Book Identity Section -->
                <div class="text-center mb-4 p-3 bg-light rounded">
                    <div class="mb-3">
                        <i class="fas fa-book fa-3x text-primary mb-2"></i>
                    </div>
                    <h4 class="text-primary mb-1 fw-bold" id="dueDateBookTitle">Book Title</h4>
                    <p class="text-muted mb-2">Update the return deadline for this book</p>
                    <span class="badge bg-info fs-6 px-3 py-1">
                        <i class="fas fa-calendar-day me-1"></i>Due Date Update
                    </span>
                </div>

                <!-- Current Due Date Section -->
                <div class="alert alert-info border-2 border-info mb-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle fa-2x text-info me-3 mt-1"></i>
                        <div>
                            <h5 class="alert-heading mb-2">📅 Current Due Date</h5>
                            <p class="mb-0 fw-bold fs-5" id="currentDueDateDisplay">Not set</p>
                        </div>
                    </div>
                </div>

                <!-- New Due Date Input -->
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-calendar-plus me-2"></i>Set New Due Date
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="newDueDateInput" class="form-label fw-semibold">
                                <i class="fas fa-calendar-day me-2 text-primary"></i>New Return Date <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control form-control-lg" id="newDueDateInput"
                                   min="" required>
                            <div class="form-text">
                                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                Due date cannot be set to a past date. Choose a future date for book return.
                            </div>
                        </div>

                        <div class="alert alert-light border">
                            <small class="text-muted">
                                <i class="fas fa-lightbulb me-1 text-warning"></i>
                                <strong>Best Practice:</strong> Setting a reasonable due date helps manage library resources effectively and ensures fair access for all users.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Impact Assessment -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-success h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h5 class="card-title text-success">Immediate Effect</h5>
                                <p class="card-text small mb-0">Due date updated instantly</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-info h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-info mb-2"></i>
                                <h5 class="card-title text-info">User Impact</h5>
                                <p class="card-text small mb-0">Borrower notified of changes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final Confirmation -->
                <div class="text-center p-3 bg-light rounded">
                    <h6 class="text-primary fw-bold mb-2">CONFIRMATION REQUIRED</h6>
                    <p class="mb-0 text-muted">Please select a new due date and click "Update Due Date" to proceed.</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-2"></i>Cancel Changes
                </button>
                <button type="button" class="btn btn-primary btn-lg px-4" onclick="submitDueDateUpdate()">
                    <i class="fas fa-save me-2"></i>Update Due Date
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Renew Book Modal -->
<div class="modal fade" id="renewBookModal" tabindex="-1" aria-labelledby="renewBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="renewBookModalLabel">
                    <i class="fas fa-arrow-clockwise me-2"></i>Renew Book
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-redo fs-1 text-warning mb-3"></i>
                    <h5 id="renewBookTitle" class="text-warning">Book Title</h5>
                    <p class="text-muted">Extend the borrowing period for this book</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-primary text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-calendar text-primary fs-2 mb-2"></i>
                                <h6 class="card-title">Current Due Date</h6>
                                <p class="card-text fw-bold" id="currentRenewDueDate">Dec 15, 2025</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-calendar-plus text-success fs-2 mb-2"></i>
                                <h6 class="card-title">New Due Date</h6>
                                <p class="card-text fw-bold" id="newRenewDueDate">Dec 29, 2025</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning border-0">
                    <h6 class="alert-heading mb-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>Renewal Details
                    </h6>
                    <ul class="mb-0 small">
                        <li><i class="fas fa-plus text-success me-1"></i>Extension period: <strong id="renewalDays">14 days</strong></li>
                        <li><i class="fas fa-info-circle text-info me-1"></i>Maximum renewals allowed: <strong>2</strong></li>
                        <li><i class="fas fa-ban text-danger me-1"></i>Cannot renew overdue books</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning" onclick="submitBookRenewal()">
                    <i class="fas fa-arrow-clockwise me-1"></i>Confirm Renewal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mark as Returned Modal -->
<div class="modal fade" id="returnBookModal" tabindex="-1" aria-labelledby="returnBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="returnBookModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Mark Book as Returned
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-book fs-1 text-success mb-3"></i>
                    <h5 id="returnBookTitle" class="text-success fw-bold">Book Title</h5>
                    <p class="text-muted">Confirm that this book has been physically returned</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="text-muted small mb-1">Borrower</div>
                            <div class="fw-semibold" id="returnBorrowerName">Student Name</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="text-muted small mb-1">Borrowed Date</div>
                            <div class="fw-semibold" id="returnBorrowedDate">Dec 1, 2025</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="text-muted small mb-1">Due Date</div>
                            <div class="fw-semibold" id="returnDueDate">Dec 15, 2025</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="text-muted small mb-1">Days Borrowed</div>
                            <div class="fw-semibold" id="returnDaysBorrowed">15 days</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0">
                    <h6 class="alert-heading mb-2">
                        <i class="fas fa-info-circle me-2"></i>What happens when you mark this book as returned?
                    </h6>
                    <ul class="mb-0 small">
                        <li><i class="fas fa-check text-success me-1"></i>Book status will be updated to "Returned"</li>
                        <li><i class="fas fa-plus text-success me-1"></i>Book will become available for other borrowers</li>
                        <li><i class="fas fa-calculator text-warning me-1"></i>Any applicable fines will be calculated</li>
                        <li><i class="fas fa-clock text-primary me-1"></i>Return date and time will be recorded</li>
                    </ul>
                </div>

                <div class="text-center">
                    <p class="text-muted mb-0">Are you sure this book has been physically returned?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" onclick="submitBookReturn()">
                    <i class="fas fa-check-circle me-1"></i>Confirm Return
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Book Modal -->
<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-labelledby="deleteBookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteBookModalLabel">
                    <i class="fas fa-trash me-2"></i>Delete Borrowing Record
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle fs-1 text-danger mb-3"></i>
                    <h5 id="deleteBookTitle" class="text-danger fw-bold">Book Title</h5>
                    <p class="text-muted">This action cannot be undone</p>
                </div>

                <div class="alert alert-danger border-0">
                    <h6 class="alert-heading mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h6>
                    <p class="mb-2">You are about to permanently delete this borrowing record. This will:</p>
                    <ul class="mb-0">
                        <li><i class="fas fa-times text-danger me-1"></i>Erase all borrowing history for this transaction</li>
                        <li><i class="fas fa-times text-danger me-1"></i>Remove any associated renewal records</li>
                        <li><i class="fas fa-times text-danger me-1"></i>Delete fine calculation history</li>
                        <li><i class="fas fa-times text-danger me-1"></i>Break audit trail for this transaction</li>
                    </ul>
                </div>

                <div class="bg-light rounded p-3 mb-3">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-muted small">Book</div>
                            <div class="fw-semibold" id="deleteModalBookTitle">Book Title</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Borrower</div>
                            <div class="fw-semibold" id="deleteModalBorrower">Student Name</div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <p class="text-danger fw-bold mb-0">Type "DELETE" to confirm:</p>
                    <input type="text" class="form-control form-control-lg text-center mt-2" id="deleteConfirmationInput" placeholder="Type DELETE here" maxlength="6">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="submitBookDeletion()" disabled>
                    <i class="fas fa-trash me-1"></i>Delete Record
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Borrowing Modal -->
<div class="modal fade" id="editBorrowingModal" tabindex="-1" aria-labelledby="editBorrowingModalLabel" aria-hidden="true" style="z-index: 1055;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editBorrowingModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Borrowing Record
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Borrowing Info Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2 text-center">
                                        <i class="fas fa-book fs-1 text-primary mb-2"></i>
                                    </div>
                                    <div class="col-md-10">
                                        <h5 id="modalBookTitle" class="text-primary mb-1">Book Title</h5>
                                        <p class="mb-2"><strong>Borrower:</strong> <span id="modalBorrowerName">Student Name</span></p>
                                        <p class="mb-2"><strong>Status:</strong> <span id="modalStatus" class="badge bg-warning">Currently Borrowed</span></p>
                                        <p class="mb-0"><strong>Due Date:</strong> <span id="modalDueDate">Dec 15, 2025</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row g-3 mb-4">
                    <!-- Edit Due Date -->
                    <div class="col-md-3">
                        <div class="card border-primary text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-calendar-edit text-primary fs-2 mb-2"></i>
                                <h6 class="card-title">Edit Due Date</h6>
                                <p class="card-text small">Change the return deadline</p>
                                <button type="button" class="btn btn-primary" onclick="showDueDateEditor()">
                                    <i class="fas fa-calendar-day me-1"></i>Edit Date
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Change Book -->
                    <div class="col-md-3">
                        <div class="card border-info text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-exchange-alt text-info fs-2 mb-2"></i>
                                <h6 class="card-title">Change Book</h6>
                                <p class="card-text small">Switch to a different book</p>
                                <button type="button" class="btn btn-info" onclick="showBookChanger()">
                                    <i class="fas fa-book me-1"></i>Change Book
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Renew Book -->
                    <div class="col-md-2" id="renewOption">
                        <div class="card border-warning text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-arrow-clockwise text-warning fs-2 mb-2"></i>
                                <h6 class="card-title">Renew</h6>
                                <p class="card-text small">Extend borrowing period</p>
                                <button type="button" class="btn btn-warning" onclick="renewBook()">
                                    <i class="fas fa-redo me-1"></i>Renew
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Mark as Returned -->
                    <div class="col-md-2" id="returnOption">
                        <div class="card border-success text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-check-circle text-success fs-2 mb-2"></i>
                                <h6 class="card-title">Return</h6>
                                <p class="card-text small">Mark book as returned</p>
                                <button type="button" class="btn btn-success" onclick="markAsReturned()">
                                    <i class="fas fa-check me-1"></i>Return
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Record -->
                    <div class="col-md-2">
                        <div class="card border-danger text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-trash text-danger fs-2 mb-2"></i>
                                <h6 class="card-title">Delete</h6>
                                <p class="card-text small">Remove this record</p>
                                <button type="button" class="btn btn-danger" onclick="deleteBorrowing()">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Due Date Editor -->
                <div id="dueDateEditor" class="card border-primary" style="display: none;">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-calendar-edit me-2"></i>Edit Due Date</h6>
                    </div>
                    <div class="card-body">
                        <form id="dueDateForm" class="row g-3">
                            <div class="col-md-6">
                                <label for="newDueDate" class="form-label fw-semibold">New Due Date</label>
                                <input type="date" class="form-control form-control-lg" id="newDueDate" required>
                                <div class="form-text">Select a new return deadline for this book</div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-primary me-2" onclick="updateDueDate()">
                                    <i class="fas fa-save me-1"></i>Update Due Date
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="hideDueDateEditor()">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Book Changer -->
                <div id="bookChanger" class="card border-info" style="display: none;">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Change Book</h6>
                    </div>
                    <div class="card-body">
                        <form id="bookChangeForm" class="row g-3">
                            <div class="col-md-8">
                                <label for="newBookSelect" class="form-label fw-semibold">Select New Book</label>
                                <select class="form-select form-select-lg" id="newBookSelect" required>
                                    <option value="">Choose a book...</option>
                                    @foreach(\App\Models\Book::where('status', 'available')->orderBy('title')->get() as $book)
                                        <option value="{{ $book->id }}">{{ $book->title }} - {{ $book->author->name ?? 'Unknown' }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">Only available books are shown</div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-info me-2" onclick="changeBook()">
                                    <i class="fas fa-exchange-alt me-1"></i>Change Book
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="hideBookChanger()">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Ensure Bootstrap is available
if (typeof bootstrap === 'undefined') {
    console.error('Bootstrap JS not loaded');
}

let currentBorrowingId = null;
let currentBorrowingStatus = null;

function openEditModal(borrowingId, bookTitle, dueDate, status, currentBookId, borrowerName) {
    try {
        console.log('Opening edit modal for borrowing:', borrowingId);
        currentBorrowingId = borrowingId;
        currentBorrowingStatus = status;

        // Set modal header info
        const modalTitle = document.getElementById('modalBookTitle');
        if (modalTitle) {
            modalTitle.textContent = bookTitle || 'Unknown Book';
        }

        const borrowerElement = document.getElementById('modalBorrowerName');
        if (borrowerElement) {
            borrowerElement.textContent = borrowerName || 'Unknown User';
        }

        // Set status badge
        const statusElement = document.getElementById('modalStatus');
        if (statusElement) {
            if (status === 'returned') {
                statusElement.className = 'badge bg-success';
                statusElement.textContent = 'Returned';
            } else {
                statusElement.className = 'badge bg-warning';
                statusElement.textContent = 'Currently Borrowed';
            }
        }

        // Set due date
        const dueDateElement = document.getElementById('modalDueDate');
        if (dueDateElement) {
            dueDateElement.textContent = dueDate ? new Date(dueDate).toLocaleDateString() : 'Not set';
        }

        // Show/hide options based on status
        const returnOption = document.getElementById('returnOption');
        const renewOption = document.getElementById('renewOption');
        if (status === 'returned') {
            if (returnOption) returnOption.style.display = 'none';
            if (renewOption) renewOption.style.display = 'none';
        } else {
            if (returnOption) returnOption.style.display = 'block';
            if (renewOption) renewOption.style.display = 'block';
        }

        // Set current due date in editor
        const dueDateInput = document.getElementById('newDueDate');
        if (dueDateInput && dueDate) {
            dueDateInput.value = dueDate;
        }

        // Reset forms and hide editors
        hideDueDateEditor();
        hideBookChanger();

        // Show modal with proper z-index
        const modalElement = document.getElementById('editBorrowingModal');
        if (modalElement) {
            modalElement.style.zIndex = '1055';
            const modalContent = modalElement.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.zIndex = '1056';
            }

            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false,
                focus: true
            });
            modal.show();
            console.log('Modal shown successfully');
        } else {
            console.error('Modal element not found');
            alert('Modal element not found. Please refresh the page.');
        }
    } catch (error) {
        console.error('Error opening modal:', error);
        alert('Error opening edit modal. Please try again.');
    }
}

function showDueDateEditor() {
    document.getElementById('dueDateEditor').style.display = 'block';
    document.getElementById('newDueDate').focus();
}

function hideDueDateEditor() {
    document.getElementById('dueDateEditor').style.display = 'none';
}

function editDueDate(borrowingId, bookTitle, currentDueDate) {
    // Set modal content
    document.getElementById('dueDateBookTitle').textContent = bookTitle;
    document.getElementById('currentDueDateDisplay').textContent = currentDueDate ? new Date(currentDueDate).toLocaleDateString() : 'Not set';

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('newDueDateInput').min = today;
    document.getElementById('newDueDateInput').value = currentDueDate || '';

    // Store borrowing ID for form submission
    window.currentDueDateBorrowingId = borrowingId;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editDueDateModal'));
    modal.show();
}

function submitDueDateUpdate() {
    const newDueDate = document.getElementById('newDueDateInput').value;

    if (!newDueDate) {
        alert('Please select a due date.');
        return;
    }

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/borrowings/${window.currentDueDateBorrowingId}/update-due-date`;
    form.style.display = 'none';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const dueDateInput = document.createElement('input');
    dueDateInput.type = 'hidden';
    dueDateInput.name = 'due_date';
    dueDateInput.value = newDueDate;
    form.appendChild(dueDateInput);

    document.body.appendChild(form);

    // Close modal and submit
    const modal = bootstrap.Modal.getInstance(document.getElementById('editDueDateModal'));
    modal.hide();
    form.submit();
}

function deleteBorrowingRecord(borrowingId, bookTitle, borrowerName) {
    // Set modal content
    document.getElementById('deleteBookTitle').textContent = bookTitle;
    document.getElementById('deleteModalBookTitle').textContent = bookTitle;
    document.getElementById('deleteModalBorrower').textContent = borrowerName || 'Unknown User';

    // Reset confirmation input
    const confirmInput = document.getElementById('deleteConfirmationInput');
    confirmInput.value = '';
    confirmInput.classList.remove('is-valid', 'is-invalid');

    // Disable confirm button initially
    document.getElementById('confirmDeleteBtn').disabled = true;

    // Add input validation
    confirmInput.addEventListener('input', function() {
        const isValid = this.value.toUpperCase() === 'DELETE';
        this.classList.toggle('is-valid', isValid);
        this.classList.toggle('is-invalid', !isValid && this.value.length > 0);
        document.getElementById('confirmDeleteBtn').disabled = !isValid;
    });

    // Store borrowing ID for form submission
    window.currentDeleteBorrowingId = borrowingId;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteBookModal'));
    modal.show();
}

function submitBookDeletion() {
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/borrowings/${window.currentDeleteBorrowingId}`;
    form.style.display = 'none';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);

    document.body.appendChild(form);

    // Close modal and submit
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteBookModal'));
    modal.hide();
    form.submit();
}

function markAsReturned() {
    if (confirm('Mark this book as returned? This will update the inventory and record the return.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/borrowings/${currentBorrowingId}/mark-as-returned`;
        form.style.display = 'none';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'POST';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}



function confirmRenew(bookTitle, currentDueDate, borrowingId) {
    const borrowingDuration = {{ \App\Models\SystemSetting::get('borrowing_duration_days', 14) }};
    const newDueDate = new Date(currentDueDate);
    newDueDate.setDate(newDueDate.getDate() + borrowingDuration);

    // Set modal content
    document.getElementById('renewBookTitle').textContent = bookTitle;
    document.getElementById('currentRenewDueDate').textContent = new Date(currentDueDate).toLocaleDateString();
    document.getElementById('newRenewDueDate').textContent = newDueDate.toLocaleDateString();
    document.getElementById('renewalDays').textContent = borrowingDuration + ' days';

    // Store borrowing ID for form submission
    window.currentRenewBorrowingId = borrowingId;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('renewBookModal'));
    modal.show();
}

function submitBookRenewal() {
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/borrowings/${window.currentRenewBorrowingId}/renew`;
    form.style.display = 'none';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    document.body.appendChild(form);

    // Close modal and submit
    const modal = bootstrap.Modal.getInstance(document.getElementById('renewBookModal'));
    modal.hide();
    form.submit();
}

function confirmReturn(borrowingId, bookTitle, borrowerName, borrowedDate) {
    const borrowedDateObj = new Date(borrowedDate);
    const today = new Date();
    const daysBorrowed = Math.ceil((today - borrowedDateObj) / (1000 * 60 * 60 * 24));

    // Set modal content
    document.getElementById('returnBookTitle').textContent = bookTitle;
    document.getElementById('returnBorrowerName').textContent = borrowerName;
    document.getElementById('returnBorrowedDate').textContent = new Date(borrowedDate).toLocaleDateString();
    document.getElementById('returnDueDate').textContent = 'Today'; // Since we're marking as returned
    document.getElementById('returnDaysBorrowed').textContent = daysBorrowed + ' days';

    // Store borrowing ID for form submission
    window.currentReturnBorrowingId = borrowingId;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('returnBookModal'));
    modal.show();
}

function submitBookReturn() {
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/borrowings/${window.currentReturnBorrowingId}/mark-as-returned`;
    form.style.display = 'none';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    document.body.appendChild(form);

    // Close modal and submit
    const modal = bootstrap.Modal.getInstance(document.getElementById('returnBookModal'));
    modal.hide();
    form.submit();
}

function changeBookPrompt(borrowingId, currentBookTitle) {
    // Simple approach - show a prompt for now since we don't have a GET route
    if (confirm(`📚 CHANGE BOOK\n\nCurrent Book: "${currentBookTitle}"\n\n⚠️ WARNING: This will reset the due date according to the new borrowing period.\n\nNote: Book changing is available through the admin interface. Please use the dedicated admin tools for this operation.`)) {
        // For now, just show the message - could redirect to admin tools or show more info
        alert('Book changing functionality is available through the admin panel. Please contact an administrator for assistance.');
    }
}

function showBookChanger() {
    hideDueDateEditor();
    document.getElementById('bookChanger').style.display = 'block';
}

function hideBookChanger() {
    document.getElementById('bookChanger').style.display = 'none';
}

function changeBook() {
    const newBookId = document.getElementById('newBookSelect').value;
    if (!newBookId) {
        alert('Please select a book to change to.');
        return;
    }

    if (confirm('Are you sure you want to change this borrowing to a different book? The due date will be reset according to the new borrowing period.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/borrowings/${currentBorrowingId}/change-book`;
        form.style.display = 'none';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const bookInput = document.createElement('input');
        bookInput.type = 'hidden';
        bookInput.name = 'new_book_id';
        bookInput.value = newBookId;
        form.appendChild(bookInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function renewBook() {
    const borrowingDuration = {{ \App\Models\SystemSetting::get('borrowing_duration_days', 14) }};
    const currentDueDate = document.getElementById('modalDueDate').textContent;
    const newDueDate = new Date(currentDueDate);
    newDueDate.setDate(newDueDate.getDate() + borrowingDuration);

    const bookTitle = document.getElementById('modalBookTitle').textContent;

    if (confirm(`🔄 RENEW BOOK: "${bookTitle}"\n\nCurrent Due Date: ${currentDueDate}\nNew Due Date: ${newDueDate.toLocaleDateString()}\n\nThis will extend the borrowing period by ${borrowingDuration} days.\n\nContinue with renewal?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/borrowings/${currentBorrowingId}/renew`;
        form.style.display = 'none';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    }
}

function deleteBorrowing() {
    const bookTitle = document.getElementById('modalBookTitle').textContent;
    const message = `🗑️ DELETE BORROWING RECORD\n\nBook: "${bookTitle}"\n\n⚠️ WARNING: This action will permanently remove this borrowing record from the system.\n\n• The borrowing history will be lost\n• Any associated fines or renewals will be removed\n• This action CANNOT be undone\n\nAre you absolutely sure you want to delete this record?`;

    if (confirm(message)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/borrowings/${currentBorrowingId}`;
        form.style.display = 'none';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function viewBorrowingDetails(borrowingId) {
    // Find the borrowing record data from the table
    const row = document.querySelector(`tr:has(button[onclick="viewBorrowingDetails(${borrowingId})"])`);
    if (!row) {
        alert('Borrowing record not found');
        return;
    }
    
    const cells = row.getElementsByTagName('td');
    const bookTitle = cells[0].querySelector('strong').textContent;
    const author = cells[0].querySelector('small').textContent;
    const borrower = cells[1].textContent;
    const borrowedDate = cells[2].textContent;
    const returnedDate = cells[3].textContent;
    const status = cells[4].querySelector('.badge').textContent;
    const dueDate = cells[5].textContent;
    
    // Create a modal or alert with the borrowing details
    const details = `
📚 BORROWING RECORD DETAILS

📖 Book: ${bookTitle}
✍️  Author: ${author}
👤 Borrower: ${borrower}
📅 Borrowed Date: ${borrowedDate}
📅 Returned Date: ${returnedDate}
🏷️  Status: ${status}
⏰ Due Date: ${dueDate}

This is a view-only record. No actions can be performed on currently borrowed or overdue books.
    `;
    
    alert(details);
}
</script>
@endsection
