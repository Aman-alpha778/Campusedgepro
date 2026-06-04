<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'faculty';

    protected $fillable = ['user_id', 'department_id', 'employee_id', 'designation', 'qualification', 'experience', 'joining_date', 'salary', 'status'];

    protected function casts(): array
    {
        return ['joining_date' => 'date', 'salary' => 'decimal:2'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
