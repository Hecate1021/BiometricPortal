<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'biometric_id',
        'secondary_biometric_id',
        'department',
        'position',
        'is_hidden' // <--- ADD THIS
    ];

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function travelOrders()
    {
        return $this->hasMany(TravelOrder::class);
    }
}
