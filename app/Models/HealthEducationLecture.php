<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthEducationLecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'address_id',
        'male_children_number',
        'female_children_number',
        'adult_men_number',
        'adult_women_number',
        'total',
        'has_special_needs',
        'is_beneficiaries',
        'beneficiary_type',
        'material_name',
        'program',
        'program_category',
        'date',
        'partner_id',
        'access_id',
        'agency_id',
        'activity_id',
        'office_id',
        'coverage_id',

    ];


    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function activity()
    {
        return $this->belongsTo(activity::class , 'activity_id');
    }
    public function coverage()
    {
        return $this->belongsTo(Coverage::class , 'coverage_id');
    }
    public function office()
    {
        return $this->belongsTo(Office::class , 'office_id');
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


   }
