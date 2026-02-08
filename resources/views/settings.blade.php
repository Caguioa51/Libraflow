@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="list-group sticky-top">
            <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="tab">Profile</a>
            <a href="#account" class="list-group-item list-group-item-action" data-bs-toggle="tab">Account</a>
            <a href="#security" class="list-group-item list-group-item-action" data-bs-toggle="tab">Security</a>
            <a href="#logout" class="list-group-item list-group-item-action text-danger" data-bs-toggle="tab">Logout</a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="profile">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">Profile Information</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id', $user->student_id) }}" readonly>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="account">
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">Account Details</div>
                    <div class="card-body">
                        <p><b>Role:</b> {{ ucfirst($user->role) }}</p>
                        <p><b>Member Since:</b> {{ $user->created_at->format('F d, Y') }}</p>
                        <p><b>Student ID:</b> {{ $user->student_id }}</p>
                    </div>
                </div>

                <!-- Barcode Section -->
                @if($user->barcode)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">Library Barcode</div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="bi bi-upc-scan fs-1 text-primary mb-2"></i>
                            <h5 class="mb-3">Your Library Barcode</h5>
                            <div class="mb-3">
                                <span class="badge bg-primary fs-4 p-3">{{ $user->barcode }}</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">📱 <strong>For Physical Scanner:</strong> Print this page or copy the number above</small><br>
                                <small class="text-muted">⌨️ <strong>For Manual Entry:</strong> Type the barcode number into the scanner</small>
                            </div>
                        </div>
                        <p class="text-muted small">Use this barcode for quick checkouts and library services</p>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm me-2" onclick="copyBarcode('{{ $user->barcode }}')">
                                <i class="bi bi-clipboard me-1"></i>Copy Number
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="printBarcode()">
                                <i class="bi bi-printer me-1"></i>Print Card
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="tab-pane fade" id="security">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">Change Password</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                            </div>
                            <button type="submit" class="btn btn-warning">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="logout">
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">Logout</div>
                    <div class="card-body">
                        <p class="mb-3">You are currently logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }}).</p>
                        <p class="mb-4">Click the button below to securely log out of your account.</p>
                        <button type="button" class="btn btn-danger btn-lg" onclick="document.getElementById('logout-form-settings').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                        <form id="logout-form-settings" method="POST" action="{{ route('logout') }}" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Enable Bootstrap tab navigation
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            var tabTrigger = new bootstrap.Tab(tab);
            tabTrigger.show();
        });
    });

    // Copy barcode to clipboard function
    function copyBarcode(barcode) {
        navigator.clipboard.writeText(barcode).then(function() {
            // Show success message
            showAlert('success', '✅ Barcode copied to clipboard!');
        }).catch(function(err) {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = barcode;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            
            // Show the same success message for fallback method
            showAlert('success', '✅ Barcode copied to clipboard!');
        });
    }

    // Print barcode card function
    function printBarcode() {
        const barcode = '{{ $user->barcode }}';
        const name = '{{ $user->name }}';
        const studentId = '{{ $user->student_id }}';

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Library Barcode Card</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        text-align: center;
                        padding: 20px;
                        margin: 0;
                    }
                    .barcode-card {
                        border: 2px solid #007bff;
                        border-radius: 10px;
                        padding: 20px;
                        max-width: 300px;
                        margin: 0 auto;
                        background: white;
                    }
                    .barcode-number {
                        font-size: 24px;
                        font-weight: bold;
                        color: #007bff;
                        font-family: monospace;
                        letter-spacing: 2px;
                        margin: 20px 0;
                        padding: 10px;
                        background: #f8f9fa;
                        border-radius: 5px;
                    }
                    .student-info {
                        margin: 15px 0;
                    }
                    .instructions {
                        font-size: 12px;
                        color: #666;
                        margin-top: 20px;
                        padding-top: 15px;
                        border-top: 1px dashed #ccc;
                    }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="barcode-card">
                    <h3>Dagupan City NHS</h3>
                    <h4>Library Card</h4>
                    <div class="student-info">
                        <p><strong>${name}</strong></p>
                        <p>Student ID: ${studentId}</p>
                    </div>
                    <div class="barcode-number">${barcode}</div>
                    <div class="instructions">
                        <p>Present this card for library services</p>
                        <p>Keep this card safe and do not share</p>
                    </div>
                </div>
                <div class="no-print" style="margin-top: 20px;">
                    <button onclick="window.print()">Print Card</button>
                    <button onclick="window.close()">Close</button>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
    }

    // Simple alert function
    function showAlert(type, message) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert.position-fixed');
        existingAlerts.forEach(alert => alert.remove());

        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Add to page
        document.body.appendChild(alertDiv);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 3000);
    }
</script>
@endsection
