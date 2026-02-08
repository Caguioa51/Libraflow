@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="bi bi-clock-history me-2"></i>Borrowing History</h2>
                    <p class="text-muted mb-0">Viewing borrowing history for: <strong>{{ $user->name }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </div>

            <!-- User Information Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>User Information</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" alt="Profile" class="rounded-circle mb-2" width="80" height="80">
                            @else
                                <div class="bg-secondary rounded-circle mb-2 d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                    <i class="bi bi-person text-white fs-2"></i>
                                </div>
                            @endif
                            <h6 class="mb-0">{{ $user->name }}</h6>
                            <small class="text-muted">{{ ucfirst($user->role) }}</small>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Email:</strong></p>
                                    <p class="mb-3">{{ $user->email }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Grade Level:</strong></p>
                                    <p class="mb-3">{{ $user->grade ?? 'N/A' }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Section:</strong></p>
                                    <p class="mb-3">{{ $user->section ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Available Books:</strong></p>
                                    <p class="mb-3">{{ $availableBooks }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Active Borrowings:</strong></p>
                                    <p class="mb-3">{{ $borrowings->where('status', 'borrowed')->count() }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <p class="mb-1"><strong>Overdue Books:</strong></p>
                                    <p class="mb-3">{{ $borrowings->where('status', 'borrowed')->where('due_date', '<', now())->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowing History Table -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Borrowing History</h5>
                </div>
                <div class="card-body">
                    @if($borrowings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Borrowed Date</th>
                                        <th>Due Date</th>
                                        <th>Return Date</th>
                                        <th>Status</th>
                                        <th>Fine</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowings as $borrowing)
                                        <tr>
                                            <td>{{ $borrowings->firstItem() + $loop->index }}</td>
                                            <td>
                                                <strong>{{ $borrowing->book->title }}</strong>
                                                @if($borrowing->book->location)
                                                    <br><small class="text-muted">📍 {{ $borrowing->book->location }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $borrowing->book->author->name ?? 'Unknown' }}</td>
                                            <td>{{ $borrowing->borrowed_at->format('M d, Y') }}</td>
                                            <td>{{ $borrowing->due_date->format('M d, Y') }}</td>
                                            <td>
                                                @if($borrowing->returned_at)
                                                    {{ $borrowing->returned_at->format('M d, Y') }}
                                                @else
                                                    <span class="badge bg-warning">Not Returned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($borrowing->status === 'returned')
                                                    <span class="badge bg-success">Returned</span>
                                                @elseif($borrowing->status === 'borrowed')
                                                    @if($borrowing->due_date < now())
                                                        <span class="badge bg-danger">Overdue</span>
                                                    @else
                                                        <span class="badge bg-primary">Active</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($borrowing->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($borrowing->fine_amount > 0)
                                                    <span class="text-danger">₱{{ number_format($borrowing->fine_amount, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(auth()->user()->isAdmin())
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Actions</span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if($borrowing->status === 'borrowed')
                                                                <li><form method="POST" action="{{ route('borrowings.renew', $borrowing) }}" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item" {{ $borrowing->canRenew() ? '' : 'disabled' }} onclick="return confirmRenew('{{ addslashes($borrowing->book->title) }}', '{{ $borrowing->due_date->format('M d, Y') }}')">
                                                                        <i class="fas fa-redo text-warning me-2"></i>Renew Book
                                                                    </button>
                                                                </form></li>
                                                                <li><form method="POST" action="{{ route('borrowings.mark-as-returned', $borrowing) }}" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item" onclick="return confirmReturn('{{ addslashes($borrowing->book->title) }}', '{{ $borrowing->user->name ?? 'Unknown User' }}', '{{ $borrowing->borrowed_at->format('M d, Y') }}')">
                                                                        <i class="fas fa-check-circle text-success me-2"></i>Mark as Returned
                                                                    </button>
                                                                </form></li>
                                                                @if($borrowing->fine_amount > 0)
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li><form method="POST" action="{{ route('borrowings.pay-fine', $borrowing) }}" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item" title="Pay outstanding fine">
                                                                            <i class="fas fa-cash text-warning me-2"></i>Pay Fine
                                                                            <span class="badge bg-danger ms-2">₱{{ number_format($borrowing->fine_amount, 0) }}</span>
                                                                        </button>
                                                                    </form></li>
                                                                @endif
                                                            @endif
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><button type="button" class="dropdown-item text-danger" onclick="deleteBorrowingRecord({{ $borrowing->id }}, '{{ addslashes($borrowing->book->title) }}')">
                                                                <i class="fas fa-trash me-2"></i>Delete Record
                                                            </button></li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $borrowings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-book fs-1 text-muted mb-3"></i>
                            <h4 class="text-muted">No borrowing history</h4>
                            <p class="text-muted">{{ $user->name }} hasn't borrowed any books yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Borrowing Modal -->
<div class="modal fade" id="editBorrowingModal" tabindex="-1" aria-labelledby="editBorrowingModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" style="z-index: 1070;">
        <div class="modal-content" style="z-index: 1080;">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editBorrowingModalLabel">
                    <i class="bi bi-pencil me-2"></i>Edit Borrowing Record
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="bi bi-book-half fs-1 text-primary mb-2"></i>
                    <h5 id="modalBookTitle" class="text-primary">Book Title</h5>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card border-primary text-center h-100">
                            <div class="card-body">
                                <i class="bi bi-calendar-date text-primary fs-2 mb-2"></i>
                                <h6 class="card-title">Edit Due Date</h6>
                                <p class="card-text small">Change the return deadline</p>
                                <button type="button" class="btn btn-primary" onclick="showDueDateEditor()">
                                    <i class="bi bi-calendar-event me-1"></i>Edit Date
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" id="returnOption">
                        <div class="card border-success text-center h-100">
                            <div class="card-body">
                                <i class="bi bi-check-circle text-success fs-2 mb-2"></i>
                                <h6 class="card-title">Mark as Returned</h6>
                                <p class="card-text small">Record book return now</p>
                                <button type="button" class="btn btn-success" onclick="markAsReturned()">
                                    <i class="bi bi-check-circle-fill me-1"></i>Return Book
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-danger text-center h-100">
                            <div class="card-body">
                                <i class="bi bi-trash text-danger fs-2 mb-2"></i>
                                <h6 class="card-title">Delete Record</h6>
                                <p class="card-text small">Remove this borrowing record</p>
                                <button type="button" class="btn btn-danger" onclick="deleteBorrowing()">
                                    <i class="bi bi-trash-fill me-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Due Date Editor (hidden by default) -->
                <div id="dueDateEditor" class="mt-4" style="display: none;">
                    <hr>
                    <h6><i class="bi bi-calendar-event me-2"></i>Edit Due Date</h6>
                    <form id="dueDateForm" class="row g-3">
                        <div class="col-md-6">
                            <label for="newDueDate" class="form-label">New Due Date</label>
                            <input type="date" class="form-control" id="newDueDate" required>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-primary me-2" onclick="updateDueDate()">
                                <i class="bi bi-check-circle me-1"></i>Update Date
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="hideDueDateEditor()">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Due Date Modal -->
<div class="modal fade" id="editDueDateModal" tabindex="-1" aria-labelledby="editDueDateModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" style="z-index: 1070;">
        <div class="modal-content" style="z-index: 1080;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editDueDateModalLabel">
                    <i class="fas fa-calendar-edit me-2"></i>Edit Due Date
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-calendar-alt fs-1 text-primary mb-3"></i>
                    <h5 id="dueDateModalTitle" class="text-primary">Edit Due Date - Book Title</h5>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Current Due Date:</strong> <span id="currentDueDateDisplay" class="fw-bold">Not set</span>
                </div>

                <div class="mb-3">
                    <label for="editDueDateInput" class="form-label fw-semibold">
                        <i class="fas fa-calendar-day me-2 text-primary"></i>New Due Date
                    </label>
                    <input type="date" class="form-control form-control-lg" id="editDueDateInput"
                           min="" required>
                    <div class="form-text">
                        <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                        Due date cannot be set to a past date.
                    </div>
                </div>

                <div class="alert alert-light border">
                    <small class="text-muted">
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Tip:</strong> Setting a reasonable due date helps manage library resources effectively.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveDueDate()">
                    <i class="fas fa-save me-1"></i>Update Due Date
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

function openEditModal(borrowingId, bookTitle, dueDate, status) {
    try {
        currentBorrowingId = borrowingId;
        currentBorrowingStatus = status;

        // Set modal content
        const modalTitle = document.getElementById('modalBookTitle');
        if (modalTitle) {
            modalTitle.textContent = bookTitle || 'Unknown Book';
        }

        // Show/hide return option based on status
        const returnOption = document.getElementById('returnOption');
        if (returnOption) {
            if (status === 'returned') {
                returnOption.style.display = 'none';
            } else {
                returnOption.style.display = 'block';
            }
        }

        // Set current due date
        const dueDateInput = document.getElementById('newDueDate');
        if (dueDateInput && dueDate) {
            dueDateInput.value = dueDate;
        }

        // Hide due date editor
        hideDueDateEditor();

        // Show modal safely
        const modalElement = document.getElementById('editBorrowingModal');
        if (modalElement && typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(modalElement, {
                backdrop: 'static',
                keyboard: false
            });
            modal.show();
        } else {
            alert('Modal system not available. Please refresh the page.');
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
    // Store the borrowing ID for the modal
    window.currentEditingBorrowingId = borrowingId;

    // Update modal content
    const modalTitle = document.getElementById('dueDateModalTitle');
    if (modalTitle) {
        modalTitle.textContent = `Edit Due Date - ${bookTitle}`;
    }

    const currentDateDisplay = document.getElementById('currentDueDateDisplay');
    if (currentDateDisplay) {
        currentDateDisplay.textContent = currentDueDate ? new Date(currentDueDate).toLocaleDateString() : 'Not set';
    }

    const dueDateInput = document.getElementById('editDueDateInput');
    if (dueDateInput) {
        dueDateInput.value = currentDueDate || '';
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        dueDateInput.min = today;
    }

    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('editDueDateModal'));
    modal.show();
}

function saveDueDate() {
    const dueDateInput = document.getElementById('editDueDateInput');
    const newDueDate = dueDateInput.value;

    if (!newDueDate) {
        alert('Please select a due date.');
        return;
    }

    const selectedDate = new Date(newDueDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        alert('Due date cannot be in the past.');
        return;
    }

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('editDueDateModal'));
    modal.hide();

    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/borrowings/${window.currentEditingBorrowingId}/update-due-date`;
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
    form.submit();
}

function deleteBorrowingRecord(borrowingId, bookTitle) {
    if (confirm(`⚠️ WARNING: Delete borrowing record for "${bookTitle}"?\n\nThis will permanently remove this borrowing record. This action cannot be undone.\n\nAre you sure?`)) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/borrowings/${borrowingId}`;
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

function confirmReturn(bookTitle, borrowerName, borrowedDate) {
    const borrowedDateObj = new Date(borrowedDate);
    const today = new Date();
    const daysBorrowed = Math.ceil((today - borrowedDateObj) / (1000 * 60 * 60 * 24));

    return confirm(`📚 MARK BOOK AS RETURNED\n\nBook: "${bookTitle}"\nBorrower: ${borrowerName}\nBorrowed: ${borrowedDate}\nDays Borrowed: ${daysBorrowed}\n\n✅ This will:\n• Mark the book as returned in the system\n• Update inventory (book becomes available)\n• Calculate any applicable fines\n• Record the return date and time\n\nContinue with return?`);
}

function confirmRenew(bookTitle, currentDueDate) {
    const borrowingDuration = {{ \App\Models\SystemSetting::get('borrowing_duration_days', 14) }};
    const newDueDate = new Date(currentDueDate);
    newDueDate.setDate(newDueDate.getDate() + borrowingDuration);

    return confirm(`🔄 RENEW BOOK: "${bookTitle}"\n\nCurrent Due Date: ${currentDueDate}\nNew Due Date: ${newDueDate.toLocaleDateString()}\n\nThis will extend the borrowing period by ${borrowingDuration} days.\n\nContinue with renewal?`);
}

function deleteBorrowing() {
    if (confirm('⚠️ WARNING: This will permanently delete this borrowing record. This action cannot be undone. Are you sure?')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/borrowings/${currentBorrowingId}`;
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
</script>
@endsection
