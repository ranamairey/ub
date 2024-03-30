<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\MedicalCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OuterMedicineOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_center_id',
        'employee_id',
        'file_path',
    ];

    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class, 'medical_center_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
}
