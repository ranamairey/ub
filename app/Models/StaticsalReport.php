<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticsalReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_center_id',
        'employee_id',
        'file_path',
        'file_type'
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




