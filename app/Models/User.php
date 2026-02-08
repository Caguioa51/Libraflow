<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'grade',
        'section',
        'password',
        'role',
        'is_approved',
        'approved_at',
        'approved_by',
        'barcode',
        'lrn_number',
        'department',
        'employee_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function borrowingRequests()
    {
        return $this->hasMany(BorrowingRequest::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isApproved()
    {
        return $this->is_approved === true;
    }

    public function isPendingApproval()
    {
        return $this->is_approved === false;
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getQrCodeAttribute()
    {
        if ($this->isAdmin()) {
            return null;
        }

        $userInfo = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'lrn_number' => $this->lrn_number,
            'barcode' => $this->barcode,
            'user_id' => $this->id
        ];

        return QrCode::format('png')->size(200)->generate(json_encode($userInfo));
    }

    public function hasQrCode()
    {
        return !$this->isAdmin();
    }

    public function getQrCodeDataAttribute()
    {
        if ($this->isAdmin()) {
            return null;
        }

        $userInfo = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'user_id' => $this->id,
            'generated_at' => now()->toISOString()
        ];

        // Add role-specific fields
        if ($this->isStudent()) {
            $userInfo['grade_level'] = $this->grade ?? 'N/A';
            $userInfo['section'] = $this->section ?? 'N/A';
        } elseif ($this->isTeacher()) {
            $userInfo['department'] = $this->department ?? 'N/A';
            $userInfo['employee_id'] = $this->employee_id ?? 'N/A';
        } else {
            // For other roles, include LRN and barcode
            $userInfo['lrn_number'] = $this->lrn_number ?? 'N/A';
            $userInfo['barcode'] = $this->barcode ?? 'N/A';
        }

        return $userInfo;
    }


}
