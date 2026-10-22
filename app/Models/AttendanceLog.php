<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model {
    protected $fillable = ['employee_id', 'punch_time', 'punch_type'];
    protected $casts = ['punch_time' => 'datetime'];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
