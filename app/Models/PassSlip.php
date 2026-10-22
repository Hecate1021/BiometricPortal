<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'transaction_details',
        'transaction_date',
        'time_from',
        'time_to',
        'unlogged_time_in_am',
        'unlogged_time_out_am',
        'unlogged_time_in_pm',
        'unlogged_time_out_pm',
        'unlogged_count',
        'status',
        'remarks',
        'dept_head_id',
        'dept_head_approved_at',
        'hrmo_id',
        'hrmo_approved_at',
    ];

    // Connects the Pass Slip to the Employee who filed it
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}