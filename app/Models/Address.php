<?php

namespace App\Models;

use App\Models\Subdistrict;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'addressable_id',
        'addressable_type',
        'subdistrict_id'
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class , 'subdistrict_id');
    }


}
