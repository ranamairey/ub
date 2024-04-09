<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'employee_id',
        'medical_center_id',
        'contract_value',
        'certificate',
        'expiration_date'
    ];
    protected $dates = ['deleted_at'];


    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }

    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class , 'medical_center_id');
    }


}
