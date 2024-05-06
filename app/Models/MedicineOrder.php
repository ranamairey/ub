<?php

namespace App\Models;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicineOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'medicine_orderable_id',
        'medicine_orderable_type',
        'quantity',
        'activity_id',
        'medical_center_medicine_id',
        'is_aprroved'
    ];

    public function orderable()
    {
        return $this->morphTo();
    }

    public function activity(){
        return $this->belongsTo(Activity::class , 'activity_id');
    }
    
}
