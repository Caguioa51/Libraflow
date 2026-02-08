@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 text-center">
                            <i class="bi bi-person-plus me-2"></i>Create Your Account
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="h4 fw-bold mb-1">Dagupan City National High School</h2>
                            <p class="text-muted mb-4">Library Management System</p>
                        </div>

                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        
                        <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input id="name" 
                                           class="form-control" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           autofocus 
                                           autocomplete="name"
                                           placeholder="Enter your full name">
                                </div>
                                <x-input-error :messages="$errors->get('name')" class="mt-1 text-danger small" />
                            </div>
                            
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
                                           autocomplete="username"
                                           placeholder="Enter your email">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label fw-medium">I am a</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <select id="role" name="role" class="form-control" required onchange="toggleGradeSection()">
                                        <option value="">Select Role</option>
                                        <option value="student">Student</option>
                                        <option value="teacher">Teacher</option>
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('role')" class="mt-1 text-danger small" />
                            </div>
                            
                            <div id="gradeSection" class="mb-3">
                                <div id="studentFields" style="display: none;">
                                    <div class="mb-3">
                                        <label for="grade" class="form-label fw-medium">Grade Level</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-mortarboard"></i></span>
                                            <select id="grade" name="grade" class="form-control">
                                                <option value="">Select Grade Level</option>
                                                <option value="7">Grade 7</option>
                                                <option value="8">Grade 8</option>
                                                <option value="9">Grade 9</option>
                                                <option value="10">Grade 10</option>
                                                <option value="11">Grade 11</option>
                                                <option value="12">Grade 12</option>
                                            </select>
                                        </div>
                                        <x-input-error :messages="$errors->get('grade')" class="mt-1 text-danger small" />
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="section" class="form-label fw-medium">Section</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                            <input id="section" 
                                                   class="form-control" 
                                                   type="text" 
                                                   name="section" 
                                                   value="{{ old('section') }}" 
                                                   placeholder="Enter your section">
                                        </div>
                                        <x-input-error :messages="$errors->get('section')" class="mt-1 text-danger small" />
                                    </div>
                                </div>
                                
                                <div id="teacherFields" style="display: none;">
                                    <div class="mb-3">
                                        <label for="department" class="form-label fw-medium">Department</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-building"></i></span>
                                            <select id="department" name="department" class="form-control">
                                                <option value="">Select Department</option>
                                                <option value="english">English</option>
                                                <option value="mathematics">Mathematics</option>
                                                <option value="science">Science</option>
                                                <option value="filipino">Filipino</option>
                                                <option value="social_studies">Social Studies</option>
                                                <option value="mapeh">MAPEH</option>
                                                <option value="tle">TLE</option>
                                                <option value="stem">STEM</option>
                                                <option value="humss">HUMSS</option>
                                                <option value="abm">ABM</option>
                                                <option value="gas">GAS</option>
                                                <option value="ict">ICT</option>
                                            </select>
                                        </div>
                                        <x-input-error :messages="$errors->get('department')" class="mt-1 text-danger small" />
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="employee_id" class="form-label fw-medium">Employee ID</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                            <input id="employee_id" 
                                                   class="form-control" 
                                                   type="text" 
                                                   name="employee_id" 
                                                   value="{{ old('employee_id') }}" 
                                                   placeholder="Enter your employee ID">
                                        </div>
                                        <x-input-error :messages="$errors->get('employee_id')" class="mt-1 text-danger small" />
                                    </div>
                                </div>
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
                                           autocomplete="new-password"
                                           placeholder="Create a password">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger small" />
                            </div>
                            
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input id="password_confirmation" 
                                           class="form-control" 
                                           type="password" 
                                           name="password_confirmation" 
                                           required 
                                           autocomplete="new-password"
                                           placeholder="Confirm your password">
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-danger small" />
                            </div>
                            
                                                        
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i> {{ __('Register') }}
                                </button>
                            </div>
                            
                            @if (Route::has('login'))
                                <div class="text-center mt-4">
                                    <p class="mb-0">
                                        Already have an account? 
                                        <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-medium">
                                            Login here
                                        </a>
                                    </p>
                                </div>
                            @endif
                        </form>
                        
                        <script>
                        function toggleGradeSection() {
                            const role = document.getElementById('role').value;
                            const gradeSection = document.getElementById('gradeSection');
                            const studentFields = document.getElementById('studentFields');
                            const teacherFields = document.getElementById('teacherFields');
                            
                            // Student fields
                            const gradeField = document.getElementById('grade');
                            const sectionField = document.getElementById('section');
                            
                            // Teacher fields
                            const departmentField = document.getElementById('department');
                            const employeeIdField = document.getElementById('employee_id');
                            
                            if (role === 'student') {
                                gradeSection.style.display = 'block';
                                studentFields.style.display = 'block';
                                teacherFields.style.display = 'none';
                                
                                // Make student fields required
                                gradeField.required = true;
                                sectionField.required = true;
                                
                                // Make teacher fields optional and clear
                                departmentField.required = false;
                                employeeIdField.required = false;
                                departmentField.value = '';
                                employeeIdField.value = '';
                            } else if (role === 'teacher') {
                                gradeSection.style.display = 'block';
                                studentFields.style.display = 'none';
                                teacherFields.style.display = 'block';
                                
                                // Make teacher fields required
                                departmentField.required = true;
                                employeeIdField.required = true;
                                
                                // Make student fields optional and clear
                                gradeField.required = false;
                                sectionField.required = false;
                                gradeField.value = '';
                                sectionField.value = '';
                            } else {
                                // Hide all fields when no role is selected
                                gradeSection.style.display = 'none';
                                studentFields.style.display = 'none';
                                teacherFields.style.display = 'none';
                                
                                // Make all fields optional and clear
                                gradeField.required = false;
                                sectionField.required = false;
                                departmentField.required = false;
                                employeeIdField.required = false;
                                gradeField.value = '';
                                sectionField.value = '';
                                departmentField.value = '';
                                employeeIdField.value = '';
                            }
                        }
                        
                        // Initialize on page load
                        document.addEventListener('DOMContentLoaded', function() {
                            toggleGradeSection();
                        });
                        </script>
                        
                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Your account will require admin approval before you can access the system.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
