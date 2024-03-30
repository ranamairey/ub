<?php

namespace App\Models;

use App\Models\District;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Governorate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function districts()
    {
        return $this->hasMany(District::class , 'governorate_id');
    }
}
