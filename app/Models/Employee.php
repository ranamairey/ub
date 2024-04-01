<?php

namespace App\Models;

use App\Models\Address;
use App\Models\Contract;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use App\Models\HealthEducationLecture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'user_name',
        'password',
        'activity'
    ];

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class , 'employee_id');
    }

    public function healtheducationlectures()
    {
        return $this->hasMany(HealthEducationLecture::class , 'employee_id');
    }

    public function employeeChoises()
    {
        return $this->hasMany(EmployeeChoise::class , 'employee_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class , 'employee_id');
    }

    // public function appointments()
    // {
    //     return $this->hasMany(Appointment::class , 'employee_id');
    // }
}
