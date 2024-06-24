<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advice extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'subject',
        'relative_activity',
        'main_img_url',
        'target_group',
    ];

    public function medicalCenter()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }

}
