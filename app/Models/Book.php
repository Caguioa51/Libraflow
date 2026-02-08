<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'category_id',
        'status',
        'genre',
        'description',
        'location',
        'quantity',
        'available_quantity',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }
    
    public function borrowingRequests(): HasMany
    {
        return $this->hasMany(BorrowingRequest::class);
    }
    
    /**
     * Check if book can be requested (has available quantity)
     */
    public function canBeRequested(): bool
    {
        return $this->available_quantity > 0;
    }
    
    /**
     * Check if book has pending requests
     */
    public function hasPendingRequests(): bool
    {
        return $this->borrowingRequests()->where('status', 'pending')->exists();
    }
    
    /**
     * Get count of pending requests
     */
    public function getPendingRequestsCount(): int
    {
        return $this->borrowingRequests()->where('status', 'pending')->count();
    }
}
