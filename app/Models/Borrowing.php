<?php

namespace App\Models;

use App\Services\DueDateCalculator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'borrowed_at',
        'returned_at',
        'status',
        'fine_amount',
        'fine_paid',
        'due_date',
        'renewal_count',
        'last_renewed_at',
    ];

    protected $casts = [
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
        'due_date' => 'datetime',
        'last_renewed_at' => 'datetime',
        'fine_amount' => 'decimal:2',
        'fine_paid' => 'boolean',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate fine amount for overdue books using system settings
     */
    public function calculateFine()
    {
        if ($this->status !== 'borrowed' || !$this->due_date) {
            return 0;
        }

        return DueDateCalculator::calculateFine($this->due_date);
    }


    /**
     * Check if the book is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'borrowed' && 
               $this->due_date && 
               now()->gt($this->due_date) &&
               !DueDateCalculator::isWithinGracePeriod($this->due_date);
    }

    /**
     * Check if the book can be renewed
     */
    public function canRenew()
    {
        $maxRenewals = \App\Models\SystemSetting::get('max_renewals', 2);
        return $this->status === 'borrowed' && 
               $this->renewal_count < $maxRenewals &&
               !$this->isOverdue();
    }

    /**
     * Renew the book using system settings
     */
    public function renew()
    {
        if (!$this->canRenew()) {
            return false;
        }

        $this->renewal_count++;
        $this->last_renewed_at = now();
        $this->due_date = DueDateCalculator::calculateDueDate(now());
        $this->save();

        return true;
    }

    /**
     * Update fine amount
     */
    public function updateFine()
    {
        $this->fine_amount = $this->calculateFine();
        $this->save();
    }

    /**
     * Get overdue days count considering grace period
     */
    public function getOverdueDays()
    {
        if (!$this->due_date || $this->status !== 'borrowed') {
            return 0;
        }

        return DueDateCalculator::calculateOverdueDays($this->due_date);
    }

    /**
     * Check if within grace period
     */
    public function isWithinGracePeriod()
    {
        if (!$this->due_date || $this->status !== 'borrowed') {
            return false;
        }

        return DueDateCalculator::isWithinGracePeriod($this->due_date);
    }

}
