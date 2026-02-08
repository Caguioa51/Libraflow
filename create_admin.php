<?php

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Hash;

// Check if admin user exists
$existingUser = App\Models\User::where('email', 'admin@gmail.com')->first();
if ($existingUser) {
    echo 'Admin user already exists, updating...' . PHP_EOL;
    $user = $existingUser;
} else {
    echo 'Creating new admin user...' . PHP_EOL;
    $user = new App\Models\User();
}

$user->name = 'Admin User';
$user->email = 'admin@gmail.com';
$user->password = Hash::make('11111111');
$user->role = 'admin';
$user->student_id = 'ADMIN001';
$user->barcode = 'RFID123456789';
$user->save();

echo 'Admin user created successfully with email: admin@gmail.com, password: 11111111, RFID: RFID123456789' . PHP_EOL;
