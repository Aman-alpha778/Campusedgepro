<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'country',
        'phone',
        'email',
        'inquiry_type',
        'message',
        'wants_updates',
    ];
}
