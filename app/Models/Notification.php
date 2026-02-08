<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
            return true;
        }
        return false;
    }

    public function markAsSent()
    {
        if (!$this->sent_at) {
            $this->update(['sent_at' => now()]);
            return true;
        }
        return false;
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isSent()
    {
        return !is_null($this->sent_at);
    }

    // Static methods for creating notifications
    public static function createReminder(User $user, $borrowing, $daysUntilDue)
    {
        return static::create([
            'user_id' => $user->id,
            'type' => 'reminder',
            'title' => 'Book Due Date Reminder',
            'message' => "Your borrowed book '{$borrowing->book->title}' is due in {$daysUntilDue} day(s). Please return it on time to avoid fines.",
            'data' => [
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book->id,
                'due_date' => $borrowing->due_date->toISOString(),
                'days_until_due' => $daysUntilDue,
            ],
        ]);
    }

    public static function createOverdue(User $user, $borrowing, $daysOverdue)
    {
        $fineAmount = $borrowing->calculateFine();

        return static::create([
            'user_id' => $user->id,
            'type' => 'overdue',
            'title' => 'Book Overdue Notice',
            'message' => "Your borrowed book '{$borrowing->book->title}' is {$daysOverdue} day(s) overdue. Please return it immediately to avoid additional fines.",
            'data' => [
                'borrowing_id' => $borrowing->id,
                'book_id' => $borrowing->book->id,
                'due_date' => $borrowing->due_date->toISOString(),
                'days_overdue' => $daysOverdue,
                'fine_amount' => $fineAmount,
            ],
        ]);
    }
}
