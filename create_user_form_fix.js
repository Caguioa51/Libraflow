// JavaScript functions for create user form
// This will be added to the create.blade.php file

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const iconId = fieldId + 'Icon';
    const icon = document.getElementById(iconId);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

function generateRfid() {
    const rfidInput = document.getElementById('rfid_card');
    const timestamp = Date.now().toString().slice(-8);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    rfidInput.value = 'RFID' + timestamp + random;
    
    // Trigger preview update
    const event = new Event('input', { bubbles: true });
    rfidInput.dispatchEvent(event);
}

function previewUser() {
    const formData = new FormData(document.getElementById('createUserForm'));
    
    // Update preview with form data
    document.getElementById('previewName').textContent = formData.get('name') || 'User Name';
    document.getElementById('previewEmail').textContent = formData.get('email') || 'email@example.com';
    document.getElementById('previewStudentId').textContent = formData.get('student_id') || 'STUDENT123';
    document.getElementById('previewRole').textContent = (formData.get('role') || 'student').charAt(0).toUpperCase() + (formData.get('role') || 'student').slice(1);
    
    const name = formData.get('name') || 'U';
    document.getElementById('previewAvatar').textContent = name.charAt(0).toUpperCase();
    
    const studentId = formData.get('student_id') || 'STUDENT123';
    document.getElementById('previewBarcode').textContent = 'STUDENT-' + studentId;
    
    // Show modal
    if (typeof bootstrap !== 'undefined') {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    }
}

function submitForm() {
    document.getElementById('createUserForm').submit();
}
