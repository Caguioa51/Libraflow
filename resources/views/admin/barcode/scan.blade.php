@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-upc-scan me-2"></i>Barcode Scanner</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Barcode Scan</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-upc-scan me-2"></i>Scan Barcode</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="barcode_input" class="form-label">Barcode Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-lg" id="barcode_input"
                                           placeholder="Scan or enter barcode number..." autocomplete="off"
                                           autofocus>
                                    <button class="btn btn-primary" type="button" id="scan_btn">
                                        <i class="bi bi-upc-scan me-2"></i>Lookup User
                                    </button>
                                    <button class="btn btn-outline-secondary" type="button" id="clear_btn">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    📷 <strong>Scanner Ready!</strong> Scan printed barcode or enter manually • Auto-lookup enabled • Press F1 for help
                                </div>

                                <!-- Status Indicator -->
                                <div id="scan_status" class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-circle-fill text-success me-1"></i>
                                        Ready to scan...
                                    </small>
                                </div>
                            </div>

                            <!-- User Info Display -->
                            <div id="user_info" class="d-none">
                                <h5>User Found:</h5>
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-3 text-center">
                                                <img id="user_photo" src="" alt="Profile" class="rounded-circle mb-3" width="80" height="80">
                                                <h6 id="user_name" class="mb-0"></h6>
                                                <small id="user_role" class="text-muted"></small>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <p class="mb-1"><strong>Email:</strong></p>
                                                        <p id="user_email" class="mb-3"></p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p class="mb-1"><strong>Student ID:</strong></p>
                                                        <p id="user_student_id" class="mb-3"></p>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <button class="btn btn-success" id="borrow_for_user">
                                                        <i class="bi bi-person-plus me-2"></i>Borrow Books for this User
                                                    </button>
                                                    <button class="btn btn-info" id="view_history">
                                                        <i class="bi bi-clock-history me-2"></i>View Borrowing History
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No User Found -->
                            <div id="no_user" class="d-none">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>No user found with this barcode.</strong>
                                    <br>
                                    <span id="no_user_message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Quick Stats -->
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Barcode Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <h3 class="text-primary mb-1">{{ \App\Models\User::whereNotNull('barcode')->count() }}</h3>
                                <p class="text-muted mb-3">Users with Barcodes</p>

                                <h5 class="text-info mb-1">{{ \App\Models\User::whereNull('barcode')->count() }}</h5>
                                <p class="text-muted">Users without Barcodes</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-people me-2"></i>Manage Users
                                </a>

                                <button class="btn btn-outline-secondary" onclick="clearScan()">
                                    <i class="bi bi-x-circle me-2"></i>Clear Scan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Barcode Scanner Enhanced Interface
class BarcodeScanner {
    constructor() {
        this.barcodeInput = document.getElementById('barcode_input');
        this.scanBtn = document.getElementById('scan_btn');
        this.clearBtn = document.getElementById('clear_btn');
        this.scanStatus = document.getElementById('scan_status');
        this.autoSubmitDelay = 800; // Auto-submit after 800ms of no input
        this.minBarcodeLength = 8; // Minimum barcode length to trigger lookup
        this.autoSubmitTimer = null;

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateStatus('Ready to scan...', 'success');
        this.showHelp();
    }

    setupEventListeners() {
        // Barcode Input with real-time processing
        this.barcodeInput.addEventListener('input', (e) => {
            this.onBarcodeInput(e.target.value);
        });

        // Manual scan button
        this.scanBtn.addEventListener('click', () => {
            this.manualLookup();
        });

        // Clear button
        this.clearBtn.addEventListener('click', () => {
            this.clearAll();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            this.handleKeyboard(e);
        });

        // Enter key on barcode input
        this.barcodeInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.manualLookup();
            }
        });
    }

    onBarcodeInput(value) {
        const cleanValue = value.replace(/[^0-9]/g, ''); // Only allow numbers
        if (cleanValue !== value) {
            this.barcodeInput.value = cleanValue;
            return;
        }

        this.updateStatus(`Scanning... (${value.length} characters)`, 'warning');

        // Clear previous timer
        if (this.autoSubmitTimer) {
            clearTimeout(this.autoSubmitTimer);
        }

        // Auto-submit if barcode looks complete
        if (value.length >= this.minBarcodeLength && value.length <= 20) {
            this.autoSubmitTimer = setTimeout(() => {
                if (this.barcodeInput.value === value) { // Check if user is still not typing
                    this.playSound('scan');
                    this.manualLookup();
                }
            }, this.autoSubmitDelay);
        }

        // Update status based on length
        if (value.length > 0 && value.length < this.minBarcodeLength) {
            this.updateStatus(`Need ${this.minBarcodeLength - value.length} more characters...`, 'info');
        }
    }

    async manualLookup() {
        const barcode = this.barcodeInput.value.trim();

        if (!barcode || barcode.length < this.minBarcodeLength) {
            this.playSound('error');
            this.updateStatus(`Need at least ${this.minBarcodeLength} characters`, 'danger');
            setTimeout(() => {
                this.updateStatus('Ready to scan...', 'success');
            }, 2000);
            return;
        }

        this.setLoading(true);
        this.updateStatus('Looking up user...', 'warning');
        this.playSound('processing');

        try {
            const response = await fetch('{{ route("admin.barcode.lookup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: 'barcode=' + encodeURIComponent(barcode)
            });

            const data = await response.json();

            if (data.success) {
                this.showUserInfo(data.user);
                this.playSound('success');
                this.updateStatus(`✅ User found: ${data.user.name}`, 'success');
            } else {
                this.showNoUser(data.message);
                this.playSound('error');
                this.updateStatus('❌ User not found', 'danger');
            }
        } catch (error) {
            console.error('Barcode Lookup Error:', error);
            this.playSound('error');
            this.updateStatus('❌ Lookup failed', 'danger');
            setTimeout(() => {
                this.updateStatus('Ready to scan...', 'success');
            }, 3000);
        } finally {
            this.setLoading(false);
        }
    }

    showUserInfo(user) {
        const userInfo = document.getElementById('user_info');
        const noUser = document.getElementById('no_user');

        userInfo.classList.remove('d-none');
        noUser.classList.add('d-none');

        // Populate user data
        document.getElementById('user_photo').src = user.profile_photo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name);
        document.getElementById('user_name').textContent = user.name;
        document.getElementById('user_email').textContent = user.email;
        document.getElementById('user_student_id').textContent = user.student_id || 'N/A';

        const roleBadge = document.getElementById('user_role');
        const roleColors = {
            'admin': 'danger',
            'teacher': 'warning',
            'student': 'info'
        };
        roleBadge.className = `badge bg-${roleColors[user.role] || 'secondary'}`;
        roleBadge.textContent = user.role.charAt(0).toUpperCase() + user.role.slice(1);

        // Setup button actions
        document.getElementById('borrow_for_user').onclick = function() {
            window.location.href = '{{ route("admin.users.borrow_for_user", "") }}/' + user.id;
        };

        document.getElementById('view_history').onclick = function() {
            window.location.href = '{{ route("admin.users.view_history", "") }}/' + user.id;
        };
    }

    showNoUser(message) {
        const userInfo = document.getElementById('user_info');
        const noUser = document.getElementById('no_user');

        userInfo.classList.add('d-none');
        noUser.classList.remove('d-none');
        document.getElementById('no_user_message').textContent = message;
    }

    clearAll() {
        this.barcodeInput.value = '';
        this.barcodeInput.focus();
        document.getElementById('user_info').classList.add('d-none');
        document.getElementById('no_user').classList.add('d-none');
        this.updateStatus('Ready to scan...', 'success');
        this.playSound('clear');

        if (this.autoSubmitTimer) {
            clearTimeout(this.autoSubmitTimer);
        }
    }

    setLoading(loading) {
        this.scanBtn.disabled = loading;
        this.scanBtn.innerHTML = loading
            ? '<i class="bi bi-hourglass-split me-2"></i>Looking up...'
            : '<i class="bi bi-upc-scan me-2"></i>Lookup User';
    }

    updateStatus(message, type) {
        const statusEl = this.scanStatus;
        if (!statusEl) return;

        const icons = {
            success: 'bi-circle-fill text-success',
            warning: 'bi-circle-fill text-warning',
            danger: 'bi-circle-fill text-danger',
            info: 'bi-circle-fill text-info'
        };

        const iconClass = icons[type] || icons.info;
        statusEl.innerHTML = `<small class="text-muted"><i class="${iconClass} me-1"></i>${message}</small>`;
    }

    playSound(type) {
        // Create audio context for beep sounds
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            const sounds = {
                success: { freq: 800, duration: 200, volume: 0.1 },
                error: { freq: 400, duration: 300, volume: 0.2 },
                processing: { freq: 600, duration: 100, volume: 0.05 },
                scan: { freq: 1000, duration: 150, volume: 0.08 },
                clear: { freq: 200, duration: 100, volume: 0.05 }
            };

            const sound = sounds[type] || sounds.processing;

            oscillator.frequency.setValueAtTime(sound.freq, audioContext.currentTime);
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(sound.volume, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + sound.duration / 1000);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + sound.duration / 1000);
        } catch (e) {
            // Silently fail if audio isn't supported
            console.log('Audio not supported');
        }
    }

    handleKeyboard(e) {
        // Keyboard shortcuts
        switch(e.key) {
            case 'F1':
                e.preventDefault();
                this.showHelp();
                break;
            case 'F2':
                e.preventDefault();
                this.clearAll();
                break;
            case 'F3':
                e.preventDefault();
                if (this.barcodeInput.value.length >= this.minBarcodeLength) {
                    this.manualLookup();
                }
                break;
            case 'Escape':
                e.preventDefault();
                this.clearAll();
                break;
        }
    }

    showHelp() {
        const helpMessage = `
🆘 Barcode Scanner Help:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
⌨️  Keyboard Shortcuts:
   F1 - Show this help
   F2 - Clear scan
   F3 - Manual lookup
   ESC - Clear and focus
   ENTER - Lookup user

📷 Scanner Usage:
   • Point scanner at barcode
   • Auto-lookup when complete
   • Manual lookup with button

🔊 Audio Feedback:
   ✅ Success beep - User found
   ❌ Error beep - User not found
   ⏳ Processing beep - Looking up

📊 Status Indicators:
   🟢 Ready to scan
   🟡 Scanning...
   🔴 Error/User not found
   🔵 Processing lookup

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
        `.trim();

        alert(helpMessage);
    }
}

// Clear scan function (global access)
function clearScan() {
    const barcodeScanner = window.barcodeScanner;
    if (barcodeScanner) {
        barcodeScanner.clearAll();
    }
}

// Initialize Barcode Scanner when page loads
document.addEventListener('DOMContentLoaded', function() {
    const barcodeScanner = new BarcodeScanner();

    // Make it globally accessible for debugging
    window.barcodeScanner = barcodeScanner;

    // Add visual feedback for scanner input
    const style = document.createElement('style');
    style.textContent = `
        #barcode_input:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }

        .scanner-ready {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .scanner-scanning {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }

        .scanner-error {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        #scan_status {
            transition: all 0.3s ease;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
