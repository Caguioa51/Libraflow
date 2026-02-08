<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {email} {password}';

    protected $description = 'Create an admin user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            $this->info("Admin user {$email} already exists, updating...");
            $existingUser->name = 'Admin User';
            $existingUser->password = Hash::make($password);
            $existingUser->role = 'admin';
            $existingUser->student_id = 'ADMIN001';
            $existingUser->barcode = 'RFID_ADMIN_' . time();
            $existingUser->save();
            $this->info("Admin user updated successfully!");
        } else {
            $this->info("Creating new admin user...");
            $user = new User();
            // Don't set ID manually - let Laravel handle auto-increment
            $user->name = 'Admin User';
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->role = 'admin';
            $user->student_id = 'ADMIN001';
            $user->barcode = 'RFID_ADMIN_' . time();
            $user->save();
            $this->info("Admin user created successfully!");
        }

        $this->info("Email: {$email}");
        $this->info("Password: {$password}");

        return 0;
    }
}
