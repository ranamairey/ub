<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'receptionist_id',
        'employee_id',
        'employee_type'
    ];
    
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class , 'medical_record_id');
    }

    public function receptionist()
    {
        return $this->belongsTo(Employee::class , 'receptionist_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }
}
