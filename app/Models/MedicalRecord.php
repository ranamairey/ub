<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'mother_name',
        'father_name',
        'gender',
        'phone_number',
        'residence_status',
        'special_needs',
        'related_person',
        'related_person_phone_number'
    ];


    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function account()
    {
        return $this->belongsTo(Account::class , 'account_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class , 'medical_record_id');
    }
}
