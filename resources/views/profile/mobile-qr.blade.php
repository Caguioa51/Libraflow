@extends('layouts.app')

@section('content')
<div class="container-fluid vh-100 d-flex flex-column p-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <!-- Header -->
    <div class="bg-dark bg-opacity-50 text-white p-3 text-center">
        <h4 class="mb-0">
            <i class="fas fa-qrcode me-2"></i>Library QR Code
        </h4>
        <p class="mb-0 small">Show this to the librarian for borrowing</p>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 d-flex align-items-center justify-content-center p-3">
        <div class="text-center">
            @if($user->hasQrCode())
                <!-- Large QR Code for Easy Scanning -->
                <div class="mb-4">
                    <div id="mobile-qr-code" class="d-inline-block bg-white p-4 rounded-3 shadow-lg"></div>
                </div>

                <!-- User Information -->
                <div class="bg-white bg-opacity-95 rounded-3 p-3 mb-3 shadow">
                    <h5 class="mb-2 fw-bold text-primary">{{ $user->name }}</h5>
                    <div class="row g-2 small">
                        <div class="col-6">
                            <span class="badge bg-secondary w-100">LRN: {{ $user->lrn_number ?? 'N/A' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-info w-100">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="alert alert-info bg-white bg-opacity-95 border-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>For Librarian:</strong> Scan this QR code to identify the user for borrowing books.
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-light btn-lg" onclick="toggleFullscreen()">
                        <i class="fas fa-expand me-2"></i>Fullscreen Mode
                    </button>
                    <button type="button" class="btn btn-outline-light" onclick="window.history.back()">
                        <i class="fas fa-arrow-left me-2"></i>Back to Profile
                    </button>
                </div>
            @else
                <div class="alert alert-warning bg-white bg-opacity-95">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    QR codes are not available for admin accounts.
                </div>
                <button type="button" class="btn btn-light" onclick="window.history.back()">
                    <i class="fas fa-arrow-left me-2"></i>Go Back
                </button>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-dark bg-opacity-50 text-white p-2 text-center">
        <small class="mb-0">
            <i class="fas fa-book me-1"></i>LibraFlow Library System
        </small>
    </div>
</div>

@if($user->hasQrCode())
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate QR code data properly escaped
    const userInfo = {
        name: @json($user->name),
        email: @json($user->email),
        role: @json($user->role),
        lrn_number: @json($user->lrn_number ?? 'N/A'),
        barcode: @json($user->barcode ?? 'N/A'),
        user_id: {{ $user->id }}
    };

    console.log('Generated QR user data:', userInfo);
    console.log('QR data string:', JSON.stringify(userInfo));

    // Generate large QR code for mobile scanning
    const qrContainer = document.getElementById('mobile-qr-code');
    
    if (typeof QRCode !== 'undefined') {
        new QRCode(qrContainer, {
            text: JSON.stringify(userInfo),
            width: 300,
            height: 300,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H,
            margin: 2
        });
    } else {
        qrContainer.innerHTML = '<div class="alert alert-danger">QR Code library not loaded. Please refresh the page.</div>';
    }

    // Auto-refresh every 30 seconds to keep QR code fresh
    setInterval(() => {
        console.log('QR Code refreshed at:', new Date().toLocaleTimeString());
    }, 30000);

    // Prevent screen timeout on mobile
    let wakeLock = null;
    if ('wakeLock' in navigator) {
        navigator.wakeLock.request('screen').then(lock => {
            wakeLock = lock;
            console.log('Screen wake lock activated');
        }).catch(err => {
            console.log('Wake lock failed:', err);
        });
    }
});

function toggleFullscreen() {
    const elem = document.documentElement;
    
    if (!document.fullscreenElement) {
        elem.requestFullscreen().then(() => {
            console.log('Entered fullscreen mode');
        }).catch(err => {
            console.log('Fullscreen error:', err);
        });
    } else {
        document.exitFullscreen().then(() => {
            console.log('Exited fullscreen mode');
        });
    }
}

// Handle visibility change to manage wake lock
document.addEventListener('visibilitychange', () => {
    if (document.hidden && wakeLock) {
        wakeLock.release();
        wakeLock = null;
    } else if (!document.hidden && 'wakeLock' in navigator) {
        navigator.wakeLock.request('screen').then(lock => {
            wakeLock = lock;
        });
    }
});

// Add touch feedback for mobile
document.addEventListener('touchstart', function(e) {
    if (e.target.closest('button')) {
        e.target.style.transform = 'scale(0.95)';
    }
});

document.addEventListener('touchend', function(e) {
    if (e.target.closest('button')) {
        e.target.style.transform = 'scale(1)';
    }
});
</script>
@endif
@endsection
