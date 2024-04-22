<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutineChildVisit extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'employee_choise_id',
        'medical_record_id',
        'current_status',
        'z_score',
        'date',
        'sam_acceptance',
        'high_energy_biscuits',
        'fat_intake',
        'micronutrients',
        'health_education',
        'nutritional_survey',
        'weight',
        'height',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class , 'medical_record_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }

    public function employeeChoise()
    {
        return $this->belongsTo(EmployeeChoise::class , 'employee_choise_id');
    }

    public function medicineOrders()
    {
        return $this->morphMany(MedicineOrder::class, 'medicine_orderable');
    }
}
