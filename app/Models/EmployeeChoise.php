<?php

namespace App\Models;

use App\Models\Access;
use App\Models\Agency;
use App\Models\Office;
use App\Models\Partner;
use App\Models\Activity;
use App\Models\Coverage;
use App\Models\Employee;
use App\Models\DoctorVisit;
use App\Models\MedicalCenter;
use App\Models\RoutineChildVisit;
use App\Models\RoutineWomenVisit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeChoise extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_id',
        'medical_center_id',
        'coverage_id',
        'office_id',
        'activity_id',
        'agency_id',
        'access_id',
        'partner_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }
    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class , 'medical_center_id');
    }
    public function coverage()
    {
        return $this->belongsTo(Coverage::class , 'coverage_id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class , 'office_id');
    }
    public function activity()
    {
        return $this->belongsTo(Activity::class , 'activity_id');
    }
    public function agency()
    {
        return $this->belongsTo(Agency::class , 'agency_id');
    }
    public function access()
    {
        return $this->belongsTo(Access::class , 'access_id');
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class , 'partner_id');
    }
    public function doctorVisits()
    {
        return $this->hasMany(DoctorVisit::class , 'employee_choise_id');
    }
    public function routineWomenVisits()
    {
        return $this->hasMany(RoutineWomenVisit::class , 'employee_choise_id');
    }
    public function routineChildVisits()
    {
        return $this->hasMany(RoutineChildVisit::class , 'employee_choise_id');
    }
    public function malnutritionWomenVisits()
    {
        return $this->hasMany(MalnutritionWomenVisit::class , 'employee_choise_id');
    }
    public function malnutritionChildVisits()
    {
        return $this->hasMany(MalnutritionChildVisit::class , 'employee_choise_id');
    }







}
