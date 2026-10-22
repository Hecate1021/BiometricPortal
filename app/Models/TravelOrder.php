<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelOrder extends Model
{
    use HasFactory;

   protected $fillable = [
        'employee_id',
        'form_number',
        'departure_from',
        'destination',
        'purpose',
        'expense_type',
        'start_date',
        'end_date',
        'status',
        'remarks',
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