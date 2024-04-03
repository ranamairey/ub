<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\MedicalCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicineWeeklyReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'medical_center_id',
        'date',
        'file'
    ];

    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class , 'medical_center_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }
}
