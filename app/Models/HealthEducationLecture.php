<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthEducationLecture extends Model
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

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
