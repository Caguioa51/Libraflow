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
        Schema::table('borrowings', function (Blueprint $table) {
            $table->decimal('fine_amount', 8, 2)->default(0)->after('status');
            $table->boolean('fine_paid')->default(false)->after('fine_amount');
            $table->timestamp('due_date')->nullable()->after('borrowed_at');
            $table->integer('renewal_count')->default(0)->after('due_date');
            $table->timestamp('last_renewed_at')->nullable()->after('renewal_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['fine_amount', 'fine_paid', 'due_date', 'renewal_count', 'last_renewed_at']);
        });
    }
};