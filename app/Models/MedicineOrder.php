<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'medicine_orderable_id',
        'medicine_orderable_type',
        'quantity',
        'activity',
        'is_aprroved'
    ];

    public function orderable()
    {
        return $this->morphTo();
    }
    
}
