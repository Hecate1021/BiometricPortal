<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

  protected $fillable = [
        'employee_id',
        'date_of_filing',
        'start_date',
        'end_date',
        // Existing 'type' column (if you still use it)
        'type', 
        // --- NEW CSC FIELDS ---
        'office_department',
        'leave_type',
        'leave_type_others',
        'leave_details',
        'details_specify_abroad',
        'details_specify_hospital',
        'details_specify_outpatient',
        'details_specify_women',
        'working_days',
        'commutation',
        'status',
        'remarks',
        
        // 🌟 NEW MULTI-TIER APPROVAL TRACKING COLUMNS 🌟
        'dept_head_id',
        'dept_head_approved_at',
        'hrmo_id',
        'hrmo_approved_at',
        'mayor_id',
        'mayor_approved_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}