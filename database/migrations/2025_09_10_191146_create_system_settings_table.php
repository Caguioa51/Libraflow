<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('system_settings')->insert([
            [
                'key' => 'borrowing_duration_days',
                'value' => '14',
                'type' => 'number',
                'description' => 'Number of days a book can be borrowed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_renewals',
                'value' => '2',
                'type' => 'number',
                'description' => 'Maximum number of renewals allowed',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'fine_per_day',
                'value' => '5.00',
                'type' => 'number',
                'description' => 'Fine amount per day for overdue books',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_books_per_user',
                'value' => '3',
                'type' => 'number',
                'description' => 'Maximum number of books a user can borrow at once',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'self_service_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable self-service checkout for students',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_notifications_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Enable email notifications for overdue books',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};