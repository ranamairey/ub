<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
