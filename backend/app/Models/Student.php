<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'campus_id', 'department_id', 'course_id', 'roll_number', 'semester', 'registration_number', 'dob', 'gender', 'address', 'guardian_name', 'guardian_phone', 'admission_date', 'status'];

    protected function casts(): array
    {
        return ['dob' => 'date', 'admission_date' => 'date'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }
}
