<?php

namespace App\Models;

use App\Models\Address;
use App\Models\Contract;
use App\Models\EmployeeChoise;
use App\Models\OuterMedicineOrder;
use App\Models\MedicineWeeklyReport;
use App\Models\MedicalCenterMedicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function outerMedicineOrders()
    {
        return $this->hasMany(OuterMedicineOrder::class , 'medical_center_id');
    }
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class , 'medical_center_id');
    }
    public function medicalCenterMedicines()
    {
        return $this->hasMany(MedicalCenterMedicine::class , 'medical_center_id');
    }
    public function employeeChoises()
    {
        return $this->hasMany(EmployeeChoise::class , 'medical_center_id');
    }
    public function medicineWeeklyReports()
    {
        return $this->hasMany(MedicineWeeklyReport::class , 'medical_center_id');
    }
    public function staticsalReports()
    {
        return $this->hasMany(StaticsalReport::class , 'medical_center_id');
    }


}
