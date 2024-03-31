<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function employeeChoises()
    {
        return $this->hasMany(EmployeeChoise::class , 'partner_id');
    }
}
