<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\MedicalRecord;
use App\Models\MalnutritionWomenVisit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WomenTreatmentProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'medical_record_id',
        'acceptance_type',
        'acceptance_reason',
        'target_weight',
        'tetanus_date',
        'vitamin_a_date',
        'end_date',
        'end_cause'
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class , 'medical_record_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class , 'employee_id');
    }
    public function malnutritionWomenVisits()
    {
        return $this->hasMany(MalnutritionWomenVisit::class , 'programs_id');
    }


}
