<?php

namespace App\Models;

use App\Models\EmployeeChoise;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coverage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function employeeChoises()
    {
        return $this->hasMany(EmployeeChoise::class , 'coverage_id');
    }
}
