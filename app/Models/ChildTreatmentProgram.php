<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\MedicalRecord;
use App\Models\MalnutritionChildVisit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChildTreatmentProgram extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'medical_record_id',
        'program_type',
        'acceptance_reason',
        'acceptance_party',
        'acceptance_type',
        'target_weight',
        'measles_vaccine_received',
        'measles_vaccine_date',
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
    public function malnutritionChildVisits()
    {
        return $this->hasMany(MalnutritionChildVisit::class , 'programs_id');
    }
}
