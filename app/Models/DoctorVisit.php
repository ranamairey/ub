<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'employee_choise_id',
        'medical_record_id',
        'health_care',
        'health_education',
        'result',
        'date'
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
