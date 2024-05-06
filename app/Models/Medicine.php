<?php

namespace App\Models;

use App\Models\MedicalCenterMedicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'scientific_name',
        'titer',
        'code',
        'unit',
        'employee_id'
    ];

    public function medicalCenterMedicines()
    {
        return $this->hasMany(MedicalCenterMedicine::class , 'medical_center_id');
    }

    


}
