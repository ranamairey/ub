<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutineWomenVisit extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'employee_choise_id',
        'medical_record_id',
        'current_status',
        'status_type',
        'z_score',
        'date',
        'IYCF',
        'nutritional_survey',
        'micronutrients',
        'high_energy_biscuits',
        'health_education'
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
