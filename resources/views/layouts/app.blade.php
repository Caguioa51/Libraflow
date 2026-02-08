<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LibraFlow') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="description" content="Dagupan City National High School Library Management System">
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="LibraFlow">

        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">

        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- QRCode.js Library -->
        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
        
        <!-- QR Code Scanning Library -->
        <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
                integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
                crossorigin="anonymous"></script>

        <!-- Fallback CSS if Vite fails -->
        <style>
            /* Fallback styles if Vite assets don't load */
            .btn { display: inline-block; padding: 0.375rem 0.75rem; margin-bottom: 0; font-size: 1rem; font-weight: 400; line-height: 1.5; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; border: 1px solid transparent; border-radius: 0.375rem; }
            .btn-primary { color: #fff; background-color: #0d6efd; border-color: #0d6efd; }
            .btn-success { color: #fff; background-color: #198754; border-color: #198754; }
            .btn-warning { color: #fff; background-color: #ffc107; border-color: #ffc107; }
            .btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
            .btn-info { color: #fff; background-color: #0dcaf0; border-color: #0dcaf0; }
            .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; border-radius: 0.25rem; }
            .table { width: 100%; margin-bottom: 1rem; color: #212529; border-collapse: collapse; }
            .table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
            .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; }
            .table-bordered { border: 1px solid #dee2e6; }
            .table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }
            .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(0,0,0,.05); }
        </style>
        <style>
            body {
                background: #ced4da !important;
                padding-top: 56px; /* Match navbar height exactly */
                min-height: 100vh;
                position: relative;
                display: flex;
                flex-direction: column;
            }
            
            /* Admin sidebar styles */
            .admin-sidebar .list-group-item {
                background: transparent !important;
                border: none !important;
                border-radius: 0.375rem !important;
                margin-bottom: 0.25rem;
                transition: all 0.2s ease;
            }
            
            .admin-sidebar .list-group-item:hover {
                background: rgba(255, 255, 255, 0.1) !important;
                transform: translateX(2px);
            }
            
            .admin-sidebar .list-group-item.active {
                background: #0d6efd !important;
                color: white !important;
                font-weight: 500;
            }
            
            .admin-sidebar .list-group-item.active:hover {
                background: #0b5ed7 !important;
            }
            
            /* Adjust main content for admin sidebar */
            body.admin-sidebar-active .container {
                margin-left: 250px !important;
                max-width: calc(100% - 250px) !important;
            }
            
            /* Hide sidebar adjustments on mobile */
            @media (max-width: 991.98px) {
                body.admin-sidebar-active .container {
                    margin-left: 0 !important;
                    max-width: 100% !important;
                }
            }
            
            .content-wrapper {
                flex: 1;
                padding-bottom: 60px; /* Space for the footer */
            }
            .navbar-brand { font-weight: bold; letter-spacing: 1px; }
            .footer { background: #222; color: #fff; padding: 1rem 0; text-align: center; margin-top: 3rem; }
            /* Only apply card styles to cards without background colors */
            .card:not(.bg-primary):not(.bg-info):not(.bg-warning):not(.bg-danger):not(.bg-success):not(.bg-secondary) {
                box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
                border: 1px solid #e9ecef !important;
                background-color: #ffffff !important;
                border-radius: 8px !important;
            }

            /* Apply shadow and border to all cards */
            .card {
                box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
                border-radius: 8px !important;
            }
            .nav-link.active, .nav-link:focus { font-weight: bold; color: #0d6efd !important; }

            /* Fix pagination styling */
            .pagination { display: flex; justify-content: center; align-items: center; margin: 1rem 0; }
            .pagination .page-link { display: inline-block; padding: 0.5rem 1rem; margin: 0 0.25rem; background-color: #fff; border: 1px solid #dee2e6; color: #0d6efd; text-decoration: none; border-radius: 0.375rem; }
            .pagination .page-link:hover { background-color: #e9ecef; border-color: #dee2e6; color: #0a58ca; }
            .pagination .page-item.active .page-link { background-color: #0d6efd; border-color: #0d6efd; color: #fff; }
            .pagination .page-item.disabled .page-link { color: #6c757d; background-color: #fff; border-color: #dee2e6; cursor: not-allowed; }

            /* Hide any problematic arrows or loading indicators */
            .loading-arrow, .loading-spinner, .vite-loading {
                display: none !important;
            }

            /* Ensure proper layout */
            .container { position: relative; z-index: 1; }
            main { position: relative; z-index: 1; }

            /* Ensure navbar is always visible */
            .navbar-collapse {
                display: flex !important;
            }

            /* Remove blue focus/active outline */
            .nav-link:focus, .nav-link:active,
            .btn:focus, .btn:active {
                outline: none !important;
                box-shadow: none !important;
            }

            /* Custom active state for nav links */
            .nav-link.bg-primary {
                background-color: #0d6efd !important;
                color: white !important;
            }

            /* User Account Dropdown Styles */
            .dropdown-menu {
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                border: 1px solid #dee2e6;
                background-color: #ffffff;
            }

            .dropdown-item {
                padding: 0.5rem 1rem;
                color: #212529;
                text-decoration: none;
                display: block;
                width: 100%;
                border: none;
                background: none;
                text-align: left;
                cursor: pointer;
            }

            .dropdown-item:hover {
                background-color: #f8f9fa;
                color: #0d6efd;
            }

            .dropdown-item.text-danger:hover {
                background-color: #f8d7da;
                color: #721c24;
            }

            .dropdown-arrow.rotated {
                transform: rotate(180deg);
                transition: transform 0.2s ease;
            }
                .navbar-collapse {
                    flex-direction: column;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    background: white;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    z-index: 1000;
                }
                .admin-sidebar {
                width: 250px;
                padding-top: 0;
                transition: all 0.3s ease;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
                z-index: 1038; /* Just below the navbar */
                position: fixed;
                top: 56px; /* Match navbar height */
                bottom: 0;
                left: 0;
                overflow-y: auto;
                }
            }

            /* Enhanced Mobile Responsive Styles */
            @media (max-width: 768px) {
                body {
                    padding-top: 56px;
                }
                
                .container, .container-fluid {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
                
                .content-wrapper {
                    margin-left: 250px;
                    padding: 1.5rem;
                    min-height: calc(100vh - 56px);
                    transition: all 0.3s ease;
                    position: relative;
                    z-index: 1;
                }
                
                main {
                    margin-left: 0 !important;
                    padding: 0.5rem !important;
                }
                
                /* Mobile card adjustments */
                .card {
                    margin-bottom: 1rem;
                    border-radius: 0.5rem;
                }
                
                .card-body {
                    padding: 1rem 0.75rem;
                }
                
                .card-header {
                    padding: 0.75rem;
                    font-size: 0.9rem;
                }
                
                /* Mobile table improvements */
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
                
                /* Mobile button adjustments */
                .btn {
                    font-size: 0.875rem;
                    padding: 0.5rem 0.75rem;
                    border-radius: 0.375rem;
                }
                
                .btn-sm {
                    font-size: 0.75rem;
                    padding: 0.375rem 0.5rem;
                }
                
                .btn-group .btn {
                    padding: 0.375rem 0.5rem;
                    font-size: 0.75rem;
                }
                
                /* Mobile form adjustments */
                .form-control, .form-select {
                    font-size: 0.875rem;
                    padding: 0.5rem 0.75rem;
                    border-radius: 0.375rem;
                }
                
                .form-label {
                    font-size: 0.875rem;
                    margin-bottom: 0.5rem;
                }
                
                /* Mobile pagination */
                .pagination .page-link {
                    padding: 0.375rem 0.75rem;
                    font-size: 0.875rem;
                    margin: 0 0.125rem;
                }
                
                /* Mobile modal adjustments */
                .modal-dialog {
                    margin: 1rem;
                    max-width: calc(100% - 2rem);
                }
                
                .modal-body {
                    padding: 1rem;
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
                
                /* Mobile dropdown adjustments */
                .dropdown-menu {
                    min-width: 200px;
                    font-size: 0.875rem;
                }
                
                .dropdown-item {
                    padding: 0.5rem 0.75rem;
                    font-size: 0.875rem;
                }
                
                /* Mobile statistics cards */
                .stat-number {
                    font-size: 1.5rem;
                }
                
                .stat-label {
                    font-size: 0.75rem;
                }
                
                /* Mobile list groups */
                .list-group-item {
                    padding: 0.75rem;
                    font-size: 0.875rem;
                }
                
                /* Mobile navigation improvements */
                .navbar-brand {
                    font-size: 1rem;
                }
                
                .navbar-toggler {
                    padding: 0.25rem 0.5rem;
                    font-size: 1.25rem;
                }
                
                /* Mobile offcanvas adjustments */
                .offcanvas {
                    width: 280px !important;
                }
                
                .offcanvas-body {
                    padding: 1rem;
                }
                
                /* Mobile text adjustments */
                h1, h2, h3, h4, h5, h6 {
                    font-size: 1.25rem;
                    margin-bottom: 1rem;
                }
                
                h1 { font-size: 1.5rem; }
                h2 { font-size: 1.375rem; }
                h3 { font-size: 1.25rem; }
                h4 { font-size: 1.125rem; }
                h5 { font-size: 1rem; }
                h6 { font-size: 0.875rem; }
                
                /* Mobile spacing adjustments */
                .row {
                    margin-left: -0.5rem;
                    margin-right: -0.5rem;
                }
                
                .col, .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, 
                .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
                    padding-left: 0.5rem;
                    padding-right: 0.5rem;
                }
                
                /* Mobile text truncation */
                .text-truncate {
                    max-width: 100%;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                
                /* Mobile touch targets */
                .btn, .nav-link, .dropdown-item, .list-group-item {
                    min-height: 44px;
                    display: flex;
                    align-items: center;
                }
                
                /* Mobile scroll improvements */
                * {
                    -webkit-overflow-scrolling: touch;
                }
            }

            /* Tablet responsive adjustments */
            @media (min-width: 769px) and (max-width: 1024px) {
                main {
                    padding: 1rem !important;
                }
                
                .container, .container-fluid {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
                
                .card-body {
                    padding: 1.25rem;
                }
                
                .table th, .table td {
                    padding: 0.75rem;
                }
            }

            /* Large desktop optimizations */
            @media (min-width: 1400px) {
                .container, .container-fluid {
                    max-width: 1320px;
                }
            }

            /* Touch device optimizations */
            @media (hover: none) and (pointer: coarse) {
                .btn:active {
                    transform: scale(0.98);
                }
                
                .nav-link:active {
                    background-color: rgba(255,255,255,0.1);
                }
                
                .card:active {
                    transform: scale(0.99);
                }
            }

            /* Landscape mobile optimizations */
            @media (max-width: 768px) and (orientation: landscape) {
                .navbar {
                    min-height: 48px;
                }
                
                body {
                    padding-top: 48px;
                }
                
                .card-body {
                    padding: 0.75rem;
                }
                
                .btn {
                    padding: 0.375rem 0.625rem;
                    font-size: 0.8125rem;
                }
            }
        </style>
    </head>
    <body>
        @include('layouts.navigation')



        <div class="container py-4">
            <main class="py-4" style="padding-top: 1rem;">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <!-- PWA Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js')
                        .then(function(registration) {
                            console.log('ServiceWorker registration successful');
                        })
                        .catch(function(err) {
                            console.log('ServiceWorker registration failed');
                        });
                });
            }
        </script>

        <!-- Ensure Bootstrap dropdown works properly -->
        <script>
            // Initialize Bootstrap dropdowns
            document.addEventListener('DOMContentLoaded', function() {
                // Make sure all dropdowns work with Bootstrap
                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });

                // Set up CSRF token for all forms
                var csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    // Set CSRF token for axios if available
                    if (window.axios) {
                        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
                    }

                // Ensure all forms have CSRF token
                var forms = document.querySelectorAll('form');
                forms.forEach(function(form) {
                    if (!form.querySelector('input[name="_token"]')) {
                        var csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken.content;
                        form.appendChild(csrfInput);
                    }
                });
            });

            // Notification functions
            function markAsRead(notificationId) {
                fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI to reflect read status
                        const notificationElement = document.querySelector(`[onclick="markAsRead(${notificationId})"]`);
                        if (notificationElement) {
                            notificationElement.classList.remove('bg-primary', 'bg-opacity-10');
                            notificationElement.classList.add('bg-light');
                            const newBadge = notificationElement.querySelector('.badge');
                            if (newBadge) {
                                newBadge.remove();
                            }
                        }
                        // Update notification count
                        updateNotificationCount();
                    }
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                });
            }

            function updateNotificationCount() {
                // Update the notification badge count
                const badge = document.querySelector('#notificationsDropdown .badge');
                if (badge) {
                    fetch('/notifications/count', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    })
                    .catch(error => {
                        console.error('Error updating notification count:', error);
                    });
                }
                }
            });
        </script>
        @stack('scripts')
        
        <!-- Toast Notification Container -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="toastMessage">
                        Operation completed successfully
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </body>
</html>
