<?php

namespace App\Models;

use App\Models\Subdistrict;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'governorate_id'
    ];


    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class , 'district_id');
    }

    public function governorate()
    {
        return $this->belongsTo(governorate::class , 'governorate_id');
    }
}
