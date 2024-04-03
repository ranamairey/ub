<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use App\Models\WomenTreatmentProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MalnutritionWomenVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'programs_id',
        'employee_choise_id',
        'muac',
        'note',
        'current_date',
        'next_visit_date',
    ];

    public function program()
    {
        return $this->belongsTo(WomenTreatmentProgram::class , 'programs_id');
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
