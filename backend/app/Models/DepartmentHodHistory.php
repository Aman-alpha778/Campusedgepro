<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentHodHistory extends Model
{
    public $timestamps = false;

    protected $table = 'department_hod_history';

    protected $fillable = ['department_id', 'old_hod', 'new_hod', 'changed_by', 'created_at'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function oldHod()
    {
        return $this->belongsTo(User::class, 'old_hod');
    }

    public function newHod()
    {
        return $this->belongsTo(User::class, 'new_hod');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
