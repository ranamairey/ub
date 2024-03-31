<?php

namespace App\Models;

use App\Models\Medicine;
use App\Models\MedicalCenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalCenterMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_center_id',
        'medicine_id',
        'quntity'
    ];

    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class , 'medical_center_id');
    }
    public function medicine()
    {
        return $this->belongsTo(Medicine::class , 'medicine_id');
    }


}
