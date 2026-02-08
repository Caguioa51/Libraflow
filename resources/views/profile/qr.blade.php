@extends('layouts.app')

@section('content')
<div class="container py-4 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 d-flex justify-content-center">
            <div class="card shadow-sm w-100" style="max-width: 400px;">
                <div class="card-body text-center">
                    <h3 class="mb-3">My Library QR Code</h3>
                    @if($user->hasQrCode())
                        <div class="mb-3 d-flex justify-content-center">
                            <div id="qr-code-display"></div>
                        </div>
                        <div class="mb-2">
                            @if($user->isStudent())
                                <span class="badge bg-secondary">LRN: {{ $user->lrn_number ?? 'N/A' }}</span>
                            @elseif($user->isTeacher())
                                <span class="badge bg-secondary">Employee ID: {{ $user->employee_id ?? 'N/A' }}</span>
                            @else
                                <span class="badge bg-secondary">LRN Number: {{ $user->lrn_number ?? 'N/A' }}</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-info">Role: {{ ucfirst($user->role) }}</span>
                        </div>
                        <p class="text-muted">Show this QR code to librarian to borrow books.</p>
                        
                        <!-- User Information Display -->
                        <div class="text-start mt-3">
                            <h6 class="fw-bold">QR Code Contains:</h6>
                            <ul class="small">
                                <li><strong>Name:</strong> {{ $user->name }}</li>
                                <li><strong>Email:</strong> {{ $user->email }}</li>
                                @if($user->isStudent())
                                    <li><strong>Grade Level:</strong> {{ $user->grade ?? 'N/A' }}</li>
                                    <li><strong>Section:</strong> {{ $user->section ?? 'N/A' }}</li>
                                @elseif($user->isTeacher())
                                    <li><strong>Department:</strong> {{ $user->department ?? 'N/A' }}</li>
                                    <li><strong>Employee ID:</strong> {{ $user->employee_id ?? 'N/A' }}</li>
                                @else
                                    <li><strong>LRN Number:</strong> {{ $user->lrn_number ?? 'N/A' }}</li>
                                @endif
                            </ul>
                        </div>
                        
                        <!-- Pending Requests with Timers -->
                        @if($pendingRequests->count() > 0)
                            <div class="mt-4">
                                <h6 class="fw-bold mb-3">Pending Borrowing Requests</h6>
                                <div class="small">
                                    @foreach($pendingRequests as $request)
                                        <div class="card mb-2">
                                            <div class="card-body p-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <strong>{{ $request['book_title'] }}</strong>
                                                        <div class="text-muted">by {{ $request['book_author'] }}</div>
                                                        <small class="text-muted">Requested: {{ $request['requested_at'] }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge bg-warning">Pending</span>
                                                        @if(isset($request['expires_at']))
                                                            <div class="timer-container mt-1" data-expires="{{ $request['expires_at'] }}">
                                                                <div class="countdown text-warning fw-bold small">Loading...</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <button type="button" class="btn btn-primary btn-sm mt-2" onclick="downloadQrCode()">
                            <i class="fas fa-download me-2"></i>Download QR Code
                        </button>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            QR codes are not available for admin accounts.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($user->hasQrCode())
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get pending borrowing requests for this user
    const pendingRequests = @json($pendingRequests);

    // Static user information for QR code (doesn't change with pending requests)
    const userInfo = {
        name: '{{ $user->name }}',
        email: '{{ $user->email }}',
        role: '{{ $user->role }}',
        user_id: {{ $user->id }},
        generated_at: new Date().toISOString()
        @if($user->isStudent())
            ,grade_level: '{{ $user->grade ?? 'N/A' }}',
            section: '{{ $user->section ?? 'N/A' }}'
        @elseif($user->isTeacher())
            ,department: '{{ $user->department ?? 'N/A' }}',
            employee_id: '{{ $user->employee_id ?? 'N/A' }}'
        @else
            ,lrn_number: '{{ $user->lrn_number ?? 'N/A' }}',
            barcode: '{{ $user->barcode ?? 'N/A' }}'
        @endif
    };

    // Generate QR code
    const qrContainer = document.getElementById('qr-code-display');
    
    if (typeof QRCode !== 'undefined') {
        new QRCode(qrContainer, {
            text: JSON.stringify(userInfo),
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    } else {
        // Fallback
        qrContainer.innerHTML = '<div class="alert alert-info">QR Code library not loaded. Please refresh the page.</div>';
    }
});

// Add countdown timer functionality
document.addEventListener('DOMContentLoaded', function() {
    const timers = document.querySelectorAll('.timer-container');
    
    timers.forEach(timer => {
        const expiresAt = new Date(timer.dataset.expires);
        const countdownElement = timer.querySelector('.countdown');
        
        if (!expiresAt || !countdownElement) return;
        
        // Calculate total duration (2 hours = 7200000 ms)
        const totalDuration = 2 * 60 * 60 * 1000;
        
        function updateCountdown() {
            const now = new Date();
            const timeLeft = expiresAt - now;
            
            if (timeLeft <= 0) {
                countdownElement.textContent = 'Expired';
                countdownElement.className = 'countdown text-muted fw-bold small';
                return;
            }
            
            const hours = Math.floor(timeLeft / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            let timeString = '';
            if (hours > 0) {
                timeString = `${hours}h ${minutes}m`;
            } else if (minutes > 0) {
                timeString = `${minutes}m ${seconds}s`;
            } else {
                timeString = `${seconds}s`;
            }
            
            countdownElement.textContent = timeString;
            
            // Change color as time runs out
            if (timeLeft < 5 * 60 * 1000) { // Less than 5 minutes
                countdownElement.className = 'countdown text-danger fw-bold small';
            } else if (timeLeft < 30 * 60 * 1000) { // Less than 30 minutes
                countdownElement.className = 'countdown text-warning fw-bold small';
            } else {
                countdownElement.className = 'countdown text-success fw-bold small';
            }
        }
        
        // Update immediately
        updateCountdown();
        
        // Update every second
        setInterval(updateCountdown, 1000);
    });
});

function downloadQrCode() {
    // Find QR code image element
    const qrContainer = document.getElementById('qr-code-display');
    const qrImage = qrContainer.querySelector('img');
    
    if (!qrImage) {
        alert('QR Code not found. Please wait for QR code to generate.');
        return;
    }
    
    // Create a canvas to convert image to downloadable format
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Set canvas size to match QR code
    canvas.width = qrImage.width || 200;
    canvas.height = qrImage.height || 200;
    
    // Draw QR code image to canvas
    ctx.drawImage(qrImage, 0, 0);
    
    // Convert canvas to blob and download
    canvas.toBlob(function(blob) {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'my_library_qr_code.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }, 'image/png');
}
</script>
@endif
@endsection 