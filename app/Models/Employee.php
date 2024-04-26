<?php

namespace App\Models;

use App\Models\Address;
use App\Models\Contract;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use App\Models\HealthEducationLecture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Model
{
    use  HasApiTokens, HasFactory,HasRolesAndAbilities;

    protected $fillable = [
        'name',
        'phone_number',
        'user_name',
        'password',
        'active',
        'is_logged'
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

    public function receptionistAppointments()
    {
        return $this->hasMany(Appointment::class , 'receptionist_id');
    }

    public function employeeAppointments()
    {
        // الطبيب او الاخصائي
        return $this->hasMany(Appointment::class , 'employee_id');
    }
    public function advices()
    {
        return $this->hasMany(Advice::class , 'employee_id');
    }

    public function medicineWeeklyReports()
    {
        return $this->hasMany(MedicineWeeklyReport::class , 'employee_id');
    }

    public function staticsalReports()
    {
        return $this->hasMany(StaticsalReport::class , 'employee_id');
    }
    public function doctorVisits()
    {
        return $this->hasMany(DoctorVisit::class , 'employee_id');
    }
    public function routineWomenVisits()
    {
        return $this->hasMany(RoutineWomenVisit::class , 'employee_id');
    }
    public function routineChildVisits()
    {
        return $this->hasMany(RoutineChildVisit::class , 'employee_id');
    }
    public function womenTreatmentPrograms()
    {
        return $this->hasMany(WomenTreatmentPrograms::class , 'employee_id');
    }
    public function childTreatmentPrograms()
    {
        return $this->hasMany(ChildTreatmentProgram::class , 'employee_id');
    }
    public function malnutritionWomenVisits()
    {
        return $this->hasMany(MalnutritionWomenVisit::class , 'employee_id');
    }
    public function malnutritionChildVisits()
    {
        return $this->hasMany(MalnutritionChildVisit::class , 'employee_id');
    }


}
