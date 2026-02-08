@extends('layouts.app')

@push('styles')
<style>
    /* Mobile responsive improvements for QR Scanner */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .py-4 {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        .card-body {
            padding: 1rem 0.75rem;
        }
        
        .card-header {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        h2.h3 {
            font-size: 1.25rem;
        }
        
        .nav-tabs .nav-link {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        
        #qr-reader {
            max-width: 100%;
            margin: 0;
            border-radius: 0.5rem;
        }
        
        #qr-video {
            height: 250px !important;
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
        
        .btn-xs {
            font-size: 0.7rem;
            padding: 0.25rem 0.375rem;
        }
        
        .form-control {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
        
        .form-label {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        
        .table {
            font-size: 0.875rem;
        }
        
        .table th, .table td {
            padding: 0.5rem;
            vertical-align: middle;
        }
        
        /* Mobile layout adjustments */
        .row > .col-lg-6 {
            margin-bottom: 1rem;
        }
        
        /* Mobile scanner adjustments */
        .scanner-section {
            order: 1;
        }
        
        .user-info-section {
            order: 2;
        }
        
        .borrowing-section {
            order: 3;
        }
        
        .recent-scans-section {
            order: 4;
        }
        
        /* Mobile button groups */
        .d-flex.gap-2 {
            flex-direction: column;
            gap: 0.5rem !important;
        }
        
        .d-flex.gap-2 .btn {
            width: 100%;
        }
        
        /* Mobile modal adjustments */
        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
        
        .modal-body {
            padding: 1rem;
        }
        
        /* Mobile search results */
        #bookSearchResults {
            max-height: 250px;
            font-size: 0.875rem;
        }
        
        .search-result-item {
            padding: 0.75rem;
        }
        
        /* Mobile badge adjustments */
        .badge {
            font-size: 0.625rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Mobile alert adjustments */
        .alert {
            padding: 0.75rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        h2.h3 {
            font-size: 1.125rem;
        }
        
        .card-header {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        #qr-video {
            height: 200px !important;
        }
        
        .btn {
            font-size: 0.8125rem;
            padding: 0.375rem 0.625rem;
        }
        
        .nav-tabs .nav-link {
            font-size: 0.8rem;
            padding: 0.375rem 0.625rem;
        }
        
        .table {
            font-size: 0.8rem;
        }
        
        .table th, .table td {
            padding: 0.375rem;
        }
    }

    /* Touch-friendly interactions */
    @media (hover: none) and (pointer: coarse) {
        .btn:active {
            transform: scale(0.98);
        }
        
        .nav-link:active {
            background-color: rgba(0,0,0,0.05);
        }
        
        .card:active {
            transform: scale(0.99);
        }
    }

    /* Landscape mobile optimizations */
    @media (max-width: 768px) and (orientation: landscape) {
        #qr-video {
            height: 180px !important;
        }
        
        .card-body {
            padding: 0.5rem;
        }
        
        .py-4 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }
    }

    /* Ensure proper scrolling */
    * {
        -webkit-overflow-scrolling: touch;
    }

    /* Hide debug buttons on production */
    .debug-buttons {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1">
                        <i class="fas fa-qrcode me-2"></i>QR Code Scanner
                    </h2>
                    <p class="text-muted mb-0">Scan user QR codes for quick borrowing</p>
                </div>
                <button type="button" class="btn btn-outline-secondary" onclick="resetScanner()">
                    <i class="fas fa-redo me-2"></i>Reset Scanner
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Scanner Section -->
        <div class="col-lg-6 mb-4 scanner-section">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-camera me-2"></i>QR Scanner
                    </h5>
                </div>
                <div class="card-body text-center">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-3" id="scannerTabs">
                        <li class="nav-item">
                            <button class="nav-link active" id="camera-tab" data-bs-toggle="tab" data-bs-target="#camera-panel">
                                <i class="fas fa-camera me-2"></i>Camera Scanner
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="upload-tab" data-bs-toggle="tab" data-bs-target="#upload-panel">
                                <i class="fas fa-upload me-2"></i>Upload QR Code
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="scannerTabContent">
                        <!-- Camera Scanner Panel -->
                        <div class="tab-pane fade show active" id="camera-panel">
                            <div id="qr-reader" style="width: 100%; max-width: 400px; margin: 0 auto; border: 2px dashed #dee2e6; border-radius: 8px; background: #f8f9fa; position: relative;">
                                <!-- Video Element -->
                                <video id="qr-video" style="width: 100%; height: 300px; border-radius: 6px; display: none;"></video>
                                
                                <!-- Canvas for QR processing -->
                                <canvas id="qr-canvas" style="display: none;"></canvas>
                                
                                <!-- Initial State -->
                                <div id="camera-initial" class="p-4">
                                    <i class="fas fa-qrcode fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Position QR code within frame to scan</p>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary" onclick="startCamera()" id="startCameraBtn">
                                            <i class="fas fa-play me-2"></i>Start Camera
                                        </button>
                                        <button type="button" class="btn btn-danger d-none" onclick="stopCamera()" id="stopCameraBtn">
                                            <i class="fas fa-stop me-2"></i>Stop Camera
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Scanning State -->
                                <div id="camera-scanning" class="p-4" style="display: none;">
                                    <div class="spinner-border text-primary mb-3"></div>
                                    <p class="text-muted mb-0">Scanning for QR codes...</p>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="stopCamera()">
                                            <i class="fas fa-stop me-2"></i>Stop Scanning
                                        </button>
                                        <!-- Debug Tests -->
                                        <div class="mt-2 debug-buttons">
                                            <small class="text-muted">Debug Tests:</small>
                                            <button type="button" class="btn btn-outline-warning btn-xs me-1" onclick="testValidQRCode()">
                                                Test Valid QR
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-xs me-1" onclick="testInvalidQRCode()">
                                                Test Invalid QR
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-xs me-1" onclick="testPlainTextQR()">
                                                Test Plain Text
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-xs me-1" onclick="showCurrentQRData()">
                                                Show Current QR Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload QR Code Panel -->
                        <div class="tab-pane fade" id="upload-panel">
                            <div style="width: 100%; max-width: 400px; margin: 0 auto; border: 2px dashed #dee2e6; border-radius: 8px; background: #f8f9fa;">
                                <div class="p-4">
                                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-3">Upload QR code image to scan</p>
                                    
                                    <!-- File Upload Area -->
                                    <div class="mb-3">
                                        <input type="file" id="qrImageUpload" accept="image/*" capture="environment" style="display: none;" onchange="handleImageUpload(event)">
                                        <label for="qrImageUpload" class="btn btn-outline-primary w-100" style="cursor: pointer;">
                                            <i class="fas fa-cloud-upload-alt me-2"></i>
                                            Choose QR Code Image
                                        </label>
                                    </div>
                                    
                                    <!-- Preview Area -->
                                    <div id="uploadPreview" class="mb-3" style="display: none;">
                                        <img id="previewImg" class="img-fluid rounded" style="max-height: 200px; border: 1px solid #dee2e6;">
                                        <p class="text-muted small mt-2">Click "Process QR Code" to scan this image</p>
                                    </div>
                                    
                                    <!-- Process Button -->
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-success w-100" onclick="processUploadedImage()" id="processUploadBtn" disabled>
                                            <i class="fas fa-qrcode me-2"></i>Process QR Code
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info Section -->
        <div class="col-lg-6 mb-4 user-info-section">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div id="user-info">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p>No user scanned yet</p>
                            <p class="small">Scan a QR code or enter user ID manually</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrowing Section -->
    <div class="row borrowing-section" id="borrowing-section" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>Quick Borrowing
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Search and Select Book</label>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshBookList()" title="Refresh book list">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                                <div class="position-relative">
                                    <input type="text" 
                                           class="form-control" 
                                           id="bookSearch" 
                                           placeholder="Search books by title, author, or category..."
                                           autocomplete="off">
                                    <input type="hidden" id="selectedBookId" required>
                                    
                                    <!-- Search Results Dropdown -->
                                    <div id="bookSearchResults" class="position-absolute w-100 bg-white border rounded shadow-sm" style="max-height: 300px; overflow-y: auto; z-index: 1000; display: none;">
                                        <div class="p-2 text-muted small">Start typing to search books...</div>
                                    </div>
                                </div>
                                
                                <!-- Selected Book Display -->
                                <div id="selectedBookDisplay" class="mt-2" style="display: none;">
                                    <div class="alert alert-success py-2">
                                        <small class="mb-0">
                                            <strong>Selected:</strong> <span id="selectedBookTitle"></span>
                                            <button type="button" class="btn btn-sm btn-outline-secondary float-end" onclick="clearBookSelection()">
                                                <i class="bi bi-x"></i> Clear
                                            </button>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="dueDate" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-lg" onclick="processBorrowing()" id="borrowBtn" disabled>
                            <i class="fas fa-check-circle me-2"></i>Process Borrowing
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Scans -->
    <div class="row mt-4 recent-scans-section">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recent Scans
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>LRN/ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="recent-scans">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No recent scans</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manual Input Modal -->
<div class="modal fade" id="manualInputModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-keyboard me-2"></i>Manual User Input
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Student ID or Email</label>
                    <input type="text" class="form-control" id="manualUserInput" placeholder="Enter student ID or email...">
                </div>
                <div class="mb-3">
                    <label class="form-label">User ID (if known)</label>
                    <input type="number" class="form-control" id="manualUserId" placeholder="Enter user ID...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="processManualInput()">Find User</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentUser = null;
let cameraActive = false;
let recentScans = [];
let uploadedImage = null;
let videoStream = null;
let scanInterval = null;
let availableBooks = [];
let searchTimeout;
let autoRefreshInterval;

// Load available books
document.addEventListener('DOMContentLoaded', function() {
    loadAvailableBooks();
    loadRecentScans();
    initializeBookSearch();
    
    // Initialize tabs
    const triggerTabList = [].slice.call(document.querySelectorAll('#scannerTabs button'));
    triggerTabList.forEach(function (triggerEl) {
        new bootstrap.Tab(triggerEl);
    });
    
    // Set up auto-refresh every 5 minutes (300000 ms)
    autoRefreshInterval = setInterval(() => {
        console.log('Auto-refreshing book list...');
        loadAvailableBooks();
    }, 300000);
});

// Clean up interval when page is unloaded
window.addEventListener('beforeunload', function() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
});

function loadAvailableBooks() {
    fetch('/api/books/available')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.books) {
                availableBooks = data.books;
                console.log('Books loaded for search:', availableBooks.length);
            } else {
                console.error('Error loading books:', data.message || 'Unknown error');
                availableBooks = [];
            }
        })
        .catch(error => {
            console.error('Error loading books:', error);
            availableBooks = [];
        });
}

function refreshBookList() {
    const refreshBtn = document.querySelector('[onclick="refreshBookList()"]');
    const originalHtml = refreshBtn.innerHTML;
    
    // Show loading state
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    refreshBtn.disabled = true;
    
    // Clear current selection
    clearBookSelection();
    
    // Reload books
    fetch('/api/books/available')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.books) {
                availableBooks = data.books;
                console.log('Books refreshed for search:', availableBooks.length);
                
                // Show success feedback
                refreshBtn.innerHTML = '<i class="fas fa-check"></i> Updated!';
                refreshBtn.classList.remove('btn-outline-primary');
                refreshBtn.classList.add('btn-success');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    refreshBtn.innerHTML = originalHtml;
                    refreshBtn.classList.remove('btn-success');
                    refreshBtn.classList.add('btn-outline-primary');
                    refreshBtn.disabled = false;
                }, 2000);
                
            } else {
                console.error('Error loading books:', data.message || 'Unknown error');
                availableBooks = [];
                
                // Show error feedback
                refreshBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error';
                refreshBtn.classList.remove('btn-outline-primary');
                refreshBtn.classList.add('btn-danger');
                
                setTimeout(() => {
                    refreshBtn.innerHTML = originalHtml;
                    refreshBtn.classList.remove('btn-danger');
                    refreshBtn.classList.add('btn-outline-primary');
                    refreshBtn.disabled = false;
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error loading books:', error);
            availableBooks = [];
            
            // Show error feedback
            refreshBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error';
            refreshBtn.classList.remove('btn-outline-primary');
            refreshBtn.classList.add('btn-danger');
            
            setTimeout(() => {
                refreshBtn.innerHTML = originalHtml;
                refreshBtn.classList.remove('btn-danger');
                refreshBtn.classList.add('btn-outline-primary');
                refreshBtn.disabled = false;
            }, 2000);
        });
}

function initializeBookSearch() {
    const bookSearchInput = document.getElementById('bookSearch');
    const searchResultsDiv = document.getElementById('bookSearchResults');
    const selectedBookId = document.getElementById('selectedBookId');
    const selectedBookTitle = document.getElementById('selectedBookTitle');
    const selectedBookDisplay = document.getElementById('selectedBookDisplay');
    const borrowBtn = document.getElementById('borrowBtn');

    if (bookSearchInput) {
        bookSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim().toLowerCase();
            
            if (query.length < 2) {
                searchResultsDiv.style.display = 'none';
                return;
            }

            // Debounce search
            searchTimeout = setTimeout(() => {
                performBookSearch(query);
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!bookSearchInput.contains(e.target) && !searchResultsDiv.contains(e.target)) {
                searchResultsDiv.style.display = 'none';
            }
        });

        // Show results when focusing the input
        bookSearchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                const query = this.value.trim().toLowerCase();
                performBookSearch(query);
            }
        });
    }

    function performBookSearch(query) {
        const results = availableBooks.filter(book => {
            return book.title.toLowerCase().includes(query) ||
                   (book.author && book.author.toLowerCase().includes(query)) ||
                   (book.category && book.category.toLowerCase().includes(query)) ||
                   (book.location && book.location.toLowerCase().includes(query));
        });

        displayBookSearchResults(results, query);
    }

    function displayBookSearchResults(results, query) {
        if (results.length === 0) {
            searchResultsDiv.innerHTML = `
                <div class="p-3 text-muted text-center">
                    <i class="fas fa-search me-2"></i>No books found matching "${query}"
                </div>
            `;
        } else {
            let html = '';
            results.forEach(book => {
                const availabilityBadge = book.available_quantity > 0 
                    ? '<span class="badge bg-success">Available: ' + book.available_quantity + '</span>'
                    : '<span class="badge bg-danger">Not Available</span>';
                
                html += `
                    <div class="search-result-item p-2 border-bottom hover-bg-light cursor-pointer" 
                         onclick="selectBook(${book.id}, '${escapeHtml(book.title)}', ${book.available_quantity})"
                         data-book-id="${book.id}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong>${escapeHtml(book.title)}</strong>
                                <br>
                                <small class="text-muted">by ${escapeHtml(book.author || 'Unknown')}</small>
                                ${book.category ? `<br><small class="text-muted">${escapeHtml(book.category)}</small>` : ''}
                                ${book.location ? `<br><small class="text-muted"><i class="fas fa-map-marker-alt"></i> ${escapeHtml(book.location)}</small>` : ''}
                            </div>
                            <div class="ms-2">
                                ${availabilityBadge}
                            </div>
                        </div>
                    </div>
                `;
            });
            searchResultsDiv.innerHTML = html;
        }
        
        searchResultsDiv.style.display = 'block';
    }

    window.selectBook = function(bookId, bookTitle, availableQuantity) {
        if (availableQuantity <= 0) {
            alert('This book is not available for borrowing.');
            return;
        }

        selectedBookId.value = bookId;
        selectedBookTitle.textContent = bookTitle;
        selectedBookDisplay.style.display = 'block';
        searchResultsDiv.style.display = 'none';
        bookSearchInput.value = bookTitle;
        borrowBtn.disabled = false;
        borrowBtn.classList.remove('btn-secondary');
        borrowBtn.classList.add('btn-success');
    };

    window.clearBookSelection = function() {
        selectedBookId.value = '';
        selectedBookDisplay.style.display = 'none';
        bookSearchInput.value = '';
        borrowBtn.disabled = true;
        borrowBtn.classList.remove('btn-success');
        borrowBtn.classList.add('btn-secondary');
        bookSearchInput.focus();
    };

    window.escapeHtml = function(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    };

    // Add hover effect styles
    const style = document.createElement('style');
    style.textContent = `
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .hover-bg-light:hover {
            background-color: #f8f9fa !important;
        }
    `;
    document.head.appendChild(style);
}

// Camera Functions
async function startCamera() {
    try {
        // Request camera access
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' }, // Prefer back camera
            audio: false
        });
        
        const video = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');
        const context = canvas.getContext('2d');
        
        // Setup video stream
        video.srcObject = videoStream;
        video.style.display = 'block';
        video.play();
        
        // Update UI
        document.getElementById('camera-initial').style.display = 'none';
        document.getElementById('camera-scanning').style.display = 'block';
        document.getElementById('startCameraBtn').classList.add('d-none');
        document.getElementById('stopCameraBtn').classList.remove('d-none');
        
        cameraActive = true;
        
        // Start scanning interval
        scanInterval = setInterval(() => {
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.height = video.videoHeight;
                canvas.width = video.videoWidth;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                // Scan for QR code using multiple approaches
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                let code = null;
                
                // 1. Normal scan
                code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: -1
                });
                
                if (!code) {
                    // 2. Try with grayscale preprocessing
                    const grayscaleData = convertToGrayscale(imageData);
                    code = jsQR(grayscaleData.data, grayscaleData.width, grayscaleData.height, {
                        inversionAttempts: -1
                    });
                }
                
                if (!code) {
                    // 3. Try with contrast enhancement
                    const enhancedData = enhanceContrast(imageData);
                    code = jsQR(enhancedData.data, enhancedData.width, enhancedData.height, {
                        inversionAttempts: -1
                    });
                }
                
                if (code) {
                    // QR code found!
                    handleQRCodeFound(code.data);
                }
            }
        }, 500); // Scan every 500ms
        
        console.log('Camera started successfully');
        
    } catch (error) {
        console.error('Camera access error:', error);
        alert('Camera access denied or not available. Please use Upload QR Code option instead.');
        
        // Fallback to upload tab
        const uploadTab = document.getElementById('upload-tab');
        uploadTab.click();
    }
}

function stopCamera() {
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
        videoStream = null;
    }
    
    if (scanInterval) {
        clearInterval(scanInterval);
        scanInterval = null;
    }
    
    const video = document.getElementById('qr-video');
    const canvas = document.getElementById('qr-canvas');
    
    video.style.display = 'none';
    canvas.style.display = 'none';
    
    // Update UI
    document.getElementById('camera-initial').style.display = 'block';
    document.getElementById('camera-scanning').style.display = 'none';
    document.getElementById('startCameraBtn').classList.remove('d-none');
    document.getElementById('stopCameraBtn').classList.add('d-none');
    
    cameraActive = false;
    console.log('Camera stopped');
}

function handleQRCodeFound(qrData) {
    try {
        console.log('QR Code Data Found:', qrData);
        console.log('QR Code Data Type:', typeof qrData);
        console.log('QR Code Data Value:', qrData);
        
        // Handle different QR code data formats
        let userData = null;
        
        if (typeof qrData === 'string') {
            try {
                userData = JSON.parse(qrData);
                console.log('Parsed JSON from string:', userData);
            } catch (jsonError) {
                console.error('JSON Parse Error:', jsonError);
                // Try to extract user info from plain text
                userData = extractUserInfoFromText(qrData);
            }
        } else if (typeof qrData === 'object') {
            userData = qrData;
            console.log('Using QR data as object:', userData);
        } else {
            console.error('Unknown QR data format');
            alert('Invalid QR code format. Please try again.');
            return;
        }
        
        // Validate required user data
        if (!userData || !userData.user_id) {
            console.error('Invalid user data structure:', userData);
            alert('QR code does not contain valid user information.');
            return;
        }
        
        // Ensure we have required fields
        if (!userData.user_id) {
            console.error('Missing user_id in QR data');
            alert('QR code missing user ID. Please check the QR code format.');
            return;
        }
        
        console.log('Final User Data:', userData);
        
        // Process the scanned data
        processScannedData(userData, 'camera');
        
    } catch (error) {
        console.error('Error processing QR data:', error);
        alert('Error reading QR code: ' + error.message);
    }
}

// Helper function to extract user info from plain text
function extractUserInfoFromText(text) {
    // Try to extract user information from plain text format
    const lines = text.split('\n');
    const userInfo = {
        user_id: null,
        name: null,
        email: null,
        role: null,
        student_id: null,
        barcode: null
    };
    
    lines.forEach(line => {
        if (line.includes('user_id:') || line.includes('userId:')) {
            userInfo.user_id = line.split(':')[1]?.trim();
        } else if (line.includes('name:') || line.includes('username:')) {
            userInfo.name = line.split(':')[1]?.trim();
        } else if (line.includes('email:')) {
            userInfo.email = line.split(':')[1]?.trim();
        } else if (line.includes('role:')) {
            userInfo.role = line.split(':')[1]?.trim();
        } else if (line.includes('student_id:')) {
            userInfo.student_id = line.split(':')[1]?.trim();
        } else if (line.includes('barcode:')) {
            userInfo.barcode = line.split(':')[1]?.trim();
        }
    });
    
    // If no structured data found, try to parse as simple key:value pairs
    if (!userInfo.user_id && !userInfo.name) {
        // Try to find any numeric ID in the text
        const numbers = text.match(/\d+/);
        if (numbers && numbers.length > 0) {
            userInfo.user_id = parseInt(numbers[0]);
            userInfo.name = 'User ' + numbers[0];
        }
    }
    
    // Ensure we have a user_id if we found a numeric ID
    if (userInfo.user_id) {
        userInfo.student_id = userInfo.user_id;
    }
    
    return userInfo;
}

// Test functions for debugging QR code scanning
function testValidQRCode() {
    console.log('Testing valid QR code...');
    
    // Test with a properly formatted QR code data
    const validQRData = {
        user_id: 12345,
        name: 'Test User',
        email: 'test@example.com',
        role: 'student',
        student_id: '2024001',
        barcode: 'BC123456'
    };
    
    console.log('Test QR data:', validQRData);
    processScannedData(validQRData, 'test');
}

function testInvalidQRCode() {
    console.log('Testing invalid QR code...');
    
    // Test with invalid JSON that should trigger error handling
    const invalidQRData = 'invalid json data {';
    
    console.log('Test invalid QR data:', invalidQRData);
    processScannedData(invalidQRData, 'test');
}

function testPlainTextQR() {
    console.log('Testing plain text QR code...');
    
    // Test with plain text that should trigger text extraction
    const plainTextData = 'user_id:12345\nname:Plain User\nemail:plain@example.com';
    
    console.log('Test plain text data:', plainTextData);
    processScannedData(plainTextData, 'test');
}

function showCurrentQRData() {
    console.log('Current QR data in system:');
    console.log('currentUser:', currentUser);
    console.log('Last scanned data:', recentScans[0]);
    
    if (currentUser) {
        alert('Current User Data:\n' + JSON.stringify(currentUser, null, 2));
    } else {
        alert('No current user data available');
    }
}

// Helper function to extract user info from QR data (unified approach)
function extractUserInfoFromQRData(qrData) {
    console.log('Extracting user info from QR data:', qrData);
    
    let userData = null;
    
    // Try to parse as JSON first
    if (typeof qrData === 'string') {
        try {
            userData = JSON.parse(qrData);
            console.log('Successfully parsed JSON:', userData);
        } catch (jsonError) {
            console.log('JSON parse failed, trying text extraction:', jsonError.message);
            // Fallback to text extraction
            userData = extractUserInfoFromText(qrData);
        }
    } else if (typeof qrData === 'object') {
        userData = qrData;
        console.log('Using QR data as object:', userData);
    } else {
        console.log('Unknown QR data format, trying text extraction');
        // Try to convert to string and extract
        const textData = String(qrData);
        userData = extractUserInfoFromText(textData);
    }
    
    // Validate and enhance user data
    if (userData) {
        // Ensure we have required fields
        if (!userData.user_id && userData.student_id) {
            userData.user_id = userData.student_id;
        }
        
        // Set defaults if missing
        if (!userData.name && userData.user_id) {
            userData.name = 'User ' + userData.user_id;
        }
        
        if (!userData.email && userData.name) {
            userData.email = userData.name.toLowerCase().replace(/\s+/g, '.') + '@library.local';
        }
        
        if (!userData.role) {
            userData.role = 'student';
        }
        
        console.log('Final extracted user data:', userData);
    }
    
    return userData;
}

// Upload Functions
function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file');
        return;
    }
    
    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Image file must be less than 5MB');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        uploadedImage = e.target.result;
        
        // Show preview
        const preview = document.getElementById('uploadPreview');
        const previewImg = document.getElementById('previewImg');
        preview.style.display = 'block';
        previewImg.src = uploadedImage;
        
        // Enable process button
        document.getElementById('processUploadBtn').disabled = false;
    };
    reader.readAsDataURL(file);
}

// Add the adjustBrightness function if it doesn't exist
if (typeof adjustBrightness === 'undefined') {
    function adjustBrightness(imageData, factor = 1.2) {
        const data = imageData.data;
        const enhancedData = new ImageData(imageData.width, imageData.height);
        const enhanced = enhancedData.data;
        
        for (let i = 0; i < data.length; i += 4) {
            enhanced[i] = Math.min(255, data[i] * factor);     // Red
            enhanced[i + 1] = Math.min(255, data[i + 1] * factor); // Green
            enhanced[i + 2] = Math.min(255, data[i + 2] * factor); // Blue
            enhanced[i + 3] = data[i + 3]; // Alpha
        }
        
        return enhancedData;
    }
}

// Replace the existing processUploadedImage function
function processUploadedImage() {
    if (!uploadedImage) {
        alert('Please upload an image first');
        return;
    }
    
    // Show loading state
    const processBtn = document.getElementById('processUploadBtn');
    const originalText = processBtn.innerHTML;
    processBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    processBtn.disabled = true;
    
    // Create image element for QR scanning
    const img = new Image();
    img.onload = function() {
        try {
            // Create canvas for QR scanning
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            
            // Set canvas size to image dimensions
            canvas.width = img.width;
            canvas.height = img.height;
            
            // Draw image on canvas
            context.drawImage(img, 0, 0, canvas.width, canvas.height);
            
            // Get image data for QR scanning
            let imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            let code = null;
            
            console.log('Attempting QR code detection...');
            
            // Define all the scanning attempts we'll try
            const scanAttempts = [
                { name: 'Normal scan', options: { inversionAttempts: 'attemptBoth' } },
                { name: 'Inverted colors', options: { inversionAttempts: 'invertFirst' } },
                { name: 'Grayscale', preprocess: convertToGrayscale, options: { inversionAttempts: 'attemptBoth' } },
                { name: 'High contrast', preprocess: enhanceContrast, options: { inversionAttempts: 'attemptBoth' } },
                { name: 'Brightness adjusted', preprocess: adjustBrightness, options: { inversionAttempts: 'attemptBoth' } }
            ];
            
            // Try each scanning approach
            for (const attempt of scanAttempts) {
                console.log(`Trying ${attempt.name}...`);
                
                let currentImageData = imageData;
                if (attempt.preprocess) {
                    currentImageData = attempt.preprocess(imageData);
                }
                
                code = jsQR(
                    currentImageData.data, 
                    currentImageData.width, 
                    currentImageData.height, 
                    attempt.options
                );
                
                if (code) {
                    console.log(`QR code found using ${attempt.name}`);
                    break;
                }
                
                // If not found, try with different scales
                const scales = [0.7, 0.8, 0.9, 1.1, 1.2, 1.5];
                for (const scale of scales) {
                    const scaledCanvas = document.createElement('canvas');
                    const scaledContext = scaledCanvas.getContext('2d');
                    const scaledWidth = Math.floor(canvas.width * scale);
                    const scaledHeight = Math.floor(canvas.height * scale);
                    
                    scaledCanvas.width = scaledWidth;
                    scaledCanvas.height = scaledHeight;
                    
                    // Draw the preprocessed image data if available
                    if (attempt.preprocess) {
                        const tempCanvas = document.createElement('canvas');
                        tempCanvas.width = currentImageData.width;
                        tempCanvas.height = currentImageData.height;
                        const tempCtx = tempCanvas.getContext('2d');
                        tempCtx.putImageData(currentImageData, 0, 0);
                        scaledContext.drawImage(tempCanvas, 0, 0, scaledWidth, scaledHeight);
                    } else {
                        scaledContext.drawImage(img, 0, 0, scaledWidth, scaledHeight);
                    }
                    
                    const scaledImageData = scaledContext.getImageData(0, 0, scaledWidth, scaledHeight);
                    code = jsQR(
                        scaledImageData.data, 
                        scaledImageData.width, 
                        scaledImageData.height, 
                        attempt.options
                    );
                    
                    if (code) {
                        console.log(`QR code found with ${attempt.name} at scale ${scale}`);
                        break;
                    }
                }
                
                if (code) break;
            }
            
            if (code) {
                // QR code found in uploaded image
                console.log('QR Code found in image:', code.data);
                const userData = extractUserInfoFromQRData(code.data);
                processScannedData(userData, 'upload');
            } else {
                // Provide helpful error message with troubleshooting tips
                console.error('No QR code detected after multiple attempts');
                showQRCodeError();
                alert('No QR code could be detected in the uploaded image.\n\n' +
                      'Please try the following:\n' +
                      '1. Ensure the QR code is clear and not blurry\n' +
                      '2. Make sure the entire QR code is visible in the image\n' +
                      '3. Try taking a new photo with better lighting\n' +
                      '4. Ensure the QR code is not at an extreme angle\n' +
                      '5. Try a higher resolution image if possible');
            }
            
        } catch (error) {
            console.error('Error processing uploaded image:', error);
            alert('Error processing image: ' + error.message);
        } finally {
            // Reset button
            processBtn.innerHTML = originalText;
            processBtn.disabled = false;
            
            // Clear upload
            document.getElementById('qrImageUpload').value = '';
            document.getElementById('uploadPreview').style.display = 'none';
            uploadedImage = null;
        }
    };
    
    img.onerror = function() {
        console.error('Failed to load uploaded image');
        alert('Failed to load the uploaded image. Please try a different image.');
        processBtn.innerHTML = originalText;
        processBtn.disabled = false;
    };
    
    img.src = uploadedImage;
}

// Helper function to convert image to grayscale
function convertToGrayscale(imageData) {
    const data = imageData.data;
    const grayscaleData = new ImageData(imageData.width, imageData.height);
    const grayscale = grayscaleData.data;
    
    for (let i = 0; i < data.length; i += 4) {
        const gray = data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114;
        grayscale[i] = gray;     // Red
        grayscale[i + 1] = gray; // Green
        grayscale[i + 2] = gray; // Blue
        grayscale[i + 3] = data[i + 3]; // Alpha
    }
    
    return grayscaleData;
}

// Helper function to enhance image contrast
function enhanceContrast(imageData) {
    const data = imageData.data;
    const enhancedData = new ImageData(imageData.width, imageData.height);
    const enhanced = enhancedData.data;
    
    // Calculate histogram
    const histogram = new Array(256).fill(0);
    for (let i = 0; i < data.length; i += 4) {
        const gray = data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114;
        histogram[Math.floor(gray)]++;
    }
    
    // Find min and max non-zero histogram values
    let min = 0, max = 255;
    for (let i = 0; i < 256; i++) {
        if (histogram[i] > 0) {
            min = i;
            break;
        }
    }
    for (let i = 255; i >= 0; i--) {
        if (histogram[i] > 0) {
            max = i;
            break;
        }
    }
    
    // Apply contrast enhancement
    const scale = 255 / (max - min);
    for (let i = 0; i < data.length; i += 4) {
        const gray = data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114;
        const enhancedGray = Math.max(0, Math.min(255, (gray - min) * scale));
        
        enhanced[i] = enhancedGray;     // Red
        enhanced[i + 1] = enhancedGray; // Green
        enhanced[i + 2] = enhancedGray; // Blue
        enhanced[i + 3] = data[i + 3];  // Alpha
    }
    
    return enhancedData;
}

// Helper function to show detailed QR code error
function showQRCodeError() {
    const errorMessage = `
No QR code detected in the uploaded image.

Possible solutions:
• Ensure the QR code is clearly visible and not blurry
• Check that the QR code is not cropped or partially hidden
• Try using a brighter, more evenly lit image
• Make sure the QR code is facing straight (not at an angle)
• Crop the image to show only the QR code
• Try a higher resolution image
• Use the camera scanner instead for better results

If you continue to have issues, please contact support.
    `;
    
    // Create a modal for better error display
    const modalHtml = `
        <div class="modal fade" id="qrErrorModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>QR Code Not Found
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <pre class="text-muted small" style="white-space: pre-wrap;">${errorMessage}</pre>
                        <div class="mt-3">
                            <button type="button" class="btn btn-primary" onclick="switchToCameraTab()">
                                <i class="fas fa-camera me-2"></i>Try Camera Scanner
                            </button>
                            <button type="button" class="btn btn-outline-secondary ms-2" onclick="document.getElementById('qrImageUpload').click()">
                                <i class="fas fa-upload me-2"></i>Upload Different Image
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if present
    const existingModal = document.getElementById('qrErrorModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to page and show it
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('qrErrorModal'));
    modal.show();
    
    // Clean up modal after hiding
    document.getElementById('qrErrorModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Helper function to switch to camera tab
function switchToCameraTab() {
    const cameraTab = document.getElementById('camera-tab');
    if (cameraTab) {
        cameraTab.click();
    }
    const modal = bootstrap.Modal.getInstance(document.getElementById('qrErrorModal'));
    if (modal) {
        modal.hide();
    }
}

function processScannedData(data, source = 'unknown') {
    try {
        console.log('Processing scanned data:', data);
        console.log('Data type:', typeof data);
        console.log('Data value:', data);
        console.log('Source:', source);
        
        // Handle different QR code data formats
        let userData = null;
        
        if (typeof data === 'string') {
            try {
                userData = JSON.parse(data);
                console.log('Successfully parsed JSON from string:', userData);
            } catch (jsonError) {
                console.log('JSON parse failed, trying text extraction:', jsonError.message);
                // Fallback to text extraction
                userData = extractUserInfoFromText(data);
            }
        } else if (typeof data === 'object') {
            userData = data;
            console.log('Using QR data as object:', userData);
        } else {
            console.log('Unknown QR data format, trying text extraction');
            // Try to convert to string and extract
            const textData = String(data);
            userData = extractUserInfoFromText(textData);
        }
        
        // Enhanced validation - check if we got valid user data
        if (!userData) {
            console.error('Failed to extract user data from QR code');
            alert('Unable to read QR code. Please try again with a clearer QR code.');
            return;
        }
        
        // More robust validation - ensure we have required fields
        if (!userData.user_id && !userData.student_id) {
            console.error('Missing user_id in QR data:', userData);
            alert('QR code must contain user ID. Please check the QR code format.');
            return;
        }
        
        console.log('Final validated user data:', userData);
        
        // Fetch fresh user data from API to get current pending requests
        fetch(`/api/users/${userData.user_id}`)
            .then(response => response.json())
            .then(data => {
                if (data.user) {
                    currentUser = data.user;
                    displayUserInfo(data.user, source);
                    showBorrowingSection();
                    addToRecentScans(data.user, source);
                } else {
                    // Fallback to QR data if API fails
                    currentUser = userData;
                    displayUserInfo(userData, source);
                    showBorrowingSection();
                    addToRecentScans(userData, source);
                }
            })
            .catch(error => {
                console.error('Error fetching fresh user data:', error);
                // Fallback to QR data if API fails
                currentUser = userData;
                displayUserInfo(userData, source);
                showBorrowingSection();
                addToRecentScans(userData, source);
            });
        
        // Stop camera if active
        if (cameraActive) {
            stopCamera();
        }
    } catch (error) {
        console.error('Error processing QR data:', error);
        alert('Error reading QR code: ' + error.message);
    }
}

function displayUserInfo(user, source = 'unknown') {
    const userInfoDiv = document.getElementById('user-info');
    
    // Check if user has pending requests
    const pendingRequests = user.pending_requests || [];
    const pendingCount = user.pending_requests_count || 0;
    
    let pendingRequestsHtml = '';
    if (pendingCount > 0) {
        pendingRequestsHtml = `
            <div class="alert alert-warning mt-3">
                <h6 class="alert-heading">
                    <i class="fas fa-clock me-2"></i>Pending Book Requests (${pendingCount})
                </h6>
                <div class="mt-2">
                    ${pendingRequests.map(request => `
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded border">
                            <div class="flex-grow-1">
                                <strong>${request.book_title}</strong>
                                <br>
                                <small class="text-muted">by ${request.book_author} | ${request.book_category}</small>
                                <br>
                                <small class="text-muted">Requested: ${new Date(request.requested_at).toLocaleDateString()}</small>
                            </div>
                            <div class="text-end">
                                <div class="mb-2">
                                    <label class="form-label small"><strong>Due Date:</strong></label>
                                    <input type="date" class="form-control form-control-sm" id="due_date_${request.id}" value="${new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]}" min="${new Date().toISOString().split('T')[0]}">
                                </div>
                                <button type="button" class="btn btn-sm btn-success" onclick="approveRequestFromQR(${request.id})">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                                <button type="button" class="btn btn-sm btn-danger ms-1" onclick="rejectRequestFromQR(${request.id})">
                                    <i class="fas fa-times me-1"></i>Reject
                                </button>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    } else {
        pendingRequestsHtml = `
            <div class="alert alert-info mt-3">
                <i class="fas fa-info-circle me-2"></i>No pending book requests
            </div>
        `;
    }
    
    userInfoDiv.innerHTML = `
        <div class="text-center">
            <div class="mb-4">
                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&color=7F9CF5&background=EBF4FF&size=100" 
                     class="rounded-circle mx-auto d-block" alt="${user.name}" style="width: 100px; height: 100px;">
            </div>
            <h5 class="mb-2">${user.name}</h5>
            <p class="text-muted mb-2">${user.email}</p>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <span class="badge bg-secondary">${user.role === 'teacher' ? 'Employee ID: ' + (user.employee_id || 'N/A') : 'LRN: ' + (user.lrn_number || 'N/A')}</span>
                </div>
                <div class="col-6">
                    <span class="badge bg-info">${user.role}</span>
                </div>
            </div>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                User identified successfully via ${source}!
            </div>
            ${pendingRequestsHtml}
        </div>
    `;
}

function rejectRequestFromQR(requestId) {
    const reason = prompt('Please enter a reason for rejection (optional):');
    
    fetch(`/borrowing-requests/${requestId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            notes: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Request rejected successfully!');
            // Refresh user info to update pending requests
            if (currentUser) {
                // Re-fetch user data to get updated pending requests
                fetch(`/api/users/${currentUser.user_id}`)
                    .then(response => response.json())
                    .then(data => {
                        currentUser = data.user;
                        displayUserInfo(data.user, 'refresh');
                    })
                    .catch(error => {
                        console.error('Error refreshing user data:', error);
                    });
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to reject request'));
        }
    })
    .catch(error => {
        console.error('Error rejecting request:', error);
        alert('Error rejecting request: ' + error.message);
    });
}

function showBorrowingSection() {
    document.getElementById('borrowing-section').style.display = 'block';
}

function approveRequestFromQR(requestId) {
    // Get the custom due date
    const dueDateInput = document.getElementById(`due_date_${requestId}`);
    const dueDate = dueDateInput ? dueDateInput.value : null;
    
    if (!dueDate) {
        alert('Please select a due date before approving.');
        return;
    }
    
    if (!confirm('Are you sure you want to approve this request? This will create a borrowing record.')) {
        return;
    }
    
    fetch(`/borrowing-requests/${requestId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            due_date: dueDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Request approved successfully!');
            // Refresh user info to update pending requests
            if (currentUser) {
                // Re-fetch user data to get updated pending requests
                fetch(`/api/users/${currentUser.user_id}`)
                    .then(response => response.json())
                    .then(data => {
                        currentUser = data.user;
                        displayUserInfo(data.user, 'refresh');
                    })
                    .catch(error => {
                        console.error('Error refreshing user data:', error);
                    });
            }
        } else {
            alert('Error: ' + (data.message || 'Failed to approve request'));
        }
    })
    .catch(error => {
        console.error('Error approving request:', error);
        alert('Error approving request: ' + error.message);
    });
}

function processBorrowing() {
    const bookId = document.getElementById('selectedBookId').value;
    const dueDate = document.getElementById('dueDate').value;
    
    if (!bookId) {
        alert('Please select a book');
        return;
    }
    
    if (!currentUser) {
        alert('No user scanned');
        return;
    }
    
    // Process borrowing
    const borrowingData = {
        user_id: parseInt(currentUser.user_id),
        book_id: parseInt(bookId),
        due_date: dueDate
    };
    
    fetch('/borrowings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(borrowingData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Borrowing processed successfully!');
            resetScanner();
        } else {
            alert('Error: ' + (data.message || 'Failed to process borrowing'));
        }
    })
    .catch(error => {
        console.error('Error processing borrowing:', error);
        alert('Failed to process borrowing');
    });
}

function showManualInput() {
    const modal = new bootstrap.Modal(document.getElementById('manualInputModal'));
    modal.show();
}

function processManualInput() {
    const input = document.getElementById('manualUserInput').value;
    const userId = document.getElementById('manualUserId').value;
    
    if (!input && !userId) {
        alert('Please enter student ID, email, or user ID');
        return;
    }
    
    // Search for user
    let searchUrl = userId ? `/api/users/${userId}` : `/api/users/search?q=${input}`;
    
    fetch(searchUrl)
        .then(response => response.json())
        .then(data => {
            if (data.user) {
                currentUser = data.user;
                displayUserInfo(data.user, 'manual');
                showBorrowingSection();
                addToRecentScans(data.user, 'manual');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('manualInputModal')).hide();
            } else {
                alert('User not found');
            }
        })
        .catch(error => {
            console.error('Error finding user:', error);
            alert('Error finding user');
        });
}

function addToRecentScans(user, source = 'unknown') {
    const scan = {
        time: new Date().toLocaleTimeString(),
        name: user.name,
        lrn_number: user.lrn_number,
        employee_id: user.employee_id,
        role: user.role,
        action: source === 'camera' ? 'Camera Scan' : (source === 'upload' ? 'Upload Scan' : 'Manual Input')
    };
    
    recentScans.unshift(scan);
    if (recentScans.length > 10) {
        recentScans.pop();
    }
    
    updateRecentScansDisplay();
}

function updateRecentScansDisplay() {
    const tbody = document.getElementById('recent-scans');
    
    if (recentScans.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No recent scans</td></tr>';
        return;
    }
    
    tbody.innerHTML = recentScans.map(scan => `
        <tr>
            <td>${scan.time}</td>
            <td>${scan.name}</td>
            <td><span class="badge bg-info">${scan.role}</span></td>
            <td>${scan.role === 'teacher' ? (scan.employee_id || 'N/A') : (scan.lrn_number || 'N/A')}</td>
            <td><span class="badge bg-${scan.action.includes('Camera') ? 'primary' : (scan.action.includes('Upload') ? 'success' : 'info')}">${scan.action}</span></td>
        </tr>
    `).join('');
}

function loadRecentScans() {
    // Load from localStorage or API
    const saved = localStorage.getItem('recentScans');
    if (saved) {
        recentScans = JSON.parse(saved);
        updateRecentScansDisplay();
    }
}

function resetScanner() {
    currentUser = null;
    document.getElementById('user-info').innerHTML = `
        <div class="text-center text-muted py-5">
            <i class="fas fa-user-slash fa-3x mb-3"></i>
            <p>No user scanned yet</p>
            <p class="small">Scan a QR code or enter user ID manually</p>
        </div>
    `;
    document.getElementById('borrowing-section').style.display = 'none';
    
    // Clear book search selection
    const selectedBookId = document.getElementById('selectedBookId');
    const selectedBookDisplay = document.getElementById('selectedBookDisplay');
    const bookSearchInput = document.getElementById('bookSearch');
    const borrowBtn = document.getElementById('borrowBtn');
    
    if (selectedBookId) selectedBookId.value = '';
    if (selectedBookDisplay) selectedBookDisplay.style.display = 'none';
    if (bookSearchInput) bookSearchInput.value = '';
    if (borrowBtn) {
        borrowBtn.disabled = true;
        borrowBtn.classList.remove('btn-success');
        borrowBtn.classList.add('btn-secondary');
    }
    
    // Reset upload
    document.getElementById('qrImageUpload').value = '';
    document.getElementById('uploadPreview').style.display = 'none';
    uploadedImage = null;
    document.getElementById('processUploadBtn').disabled = true;
    
    // Stop camera if active
    if (cameraActive) {
        stopCamera();
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
    }
    if (scanInterval) {
        clearInterval(scanInterval);
    }
    
    // Save recent scans to localStorage
    localStorage.setItem('recentScans', JSON.stringify(recentScans));
});
</script>
@endsection
