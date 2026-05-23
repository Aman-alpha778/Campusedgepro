<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class DemoUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'request_id',
        'username',
        'password',
        'expiry_date',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'expiry_date' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function demoRequest(): BelongsTo
    {
        return $this->belongsTo(DemoRequest::class, 'request_id');
    }

    public function isExpired(): bool
    {
        return $this->expiry_date instanceof Carbon
            ? $this->expiry_date->isPast()
            : Carbon::parse($this->expiry_date)->isPast();
    }
}
