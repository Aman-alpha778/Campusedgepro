<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DemoRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_name',
        'admin_name',
        'email',
        'phone',
        'student_strength',
        'requirements',
        'status',
    ];

    public function demoUser(): HasOne
    {
        return $this->hasOne(DemoUser::class, 'request_id');
    }
}
