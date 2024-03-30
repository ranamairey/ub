<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'medical_center_id',
        'contract_value',
        'certificate',
        'expiration_date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }

    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class , 'medical_center_id');
    }


}
