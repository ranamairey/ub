<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'district_id'
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class , 'subdistrict_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class , 'district_id');
    }


}
