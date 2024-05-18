<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\MedicalCenterMedicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicineOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'medicine_orderable_id',
        'medicine_orderable_type',
        'quantity',
        'activity_id',
        'medical_center_medicine_id',
        'is_aprroved'
    ];

    protected $dates = ['deleted_at'];

    public function orderable()
    {
        return $this->morphTo();
    }

    public function activity(){
        return $this->belongsTo(Activity::class , 'activity_id');
    }

    public function medicalCenterMedicine(){
        return $this->belongsTo(MedicalCenterMedicine::class , 'medical_center_medicine_id');
    }
    
}
