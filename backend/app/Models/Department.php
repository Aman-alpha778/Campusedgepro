<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'description',
        'email',
        'phone',
        'location',
        'hod_id',
        'established_year',
        'total_intake',
        'status',
        'created_by',
        'updated_by',
    ];

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function faculty()
    {
        return $this->hasMany(Faculty::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function hodHistory()
    {
        return $this->hasMany(DepartmentHodHistory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSearch($query, ?string $search)
    {
        return $query->when($search, fn ($query) => $query->where(function ($query) use ($search): void {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }));
    }
}
