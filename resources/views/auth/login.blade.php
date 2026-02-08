@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 text-center">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login to Your Account
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="h4 fw-bold mb-1">Dagupan City National High School</h2>
                            <p class="text-muted mb-4">Library Management System</p>
                        </div>

                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        
                        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input id="email" 
                                           class="form-control" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus 
                                           autocomplete="username"
                                           placeholder="Enter your email">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input id="password" 
                                           class="form-control" 
                                           type="password" 
                                           name="password" 
                                           required 
                                           autocomplete="current-password"
                                           placeholder="Enter your password">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger small" />
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">
                                    {{ __('Remember me') }}
                                </label>
                            </div>
                            
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('Log in') }}
                                </button>
                            </div>
                            
                            @if (Route::has('register'))
                                <div class="text-center mt-4">
                                    <p class="mb-0">
                                        Don't have an account? 
                                        <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-medium">
                                            Register here
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            </small>
        </div>
    </div>
</div>
@endsection
