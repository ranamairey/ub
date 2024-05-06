<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function employeeChoises()
    {
        return $this->hasMany(EmployeeChoise::class , 'activity_id');
    }
    public function medicineOrders()
    {
        return $this->hasMany(MedicineOrder::class , 'activity_id');
    }
}
