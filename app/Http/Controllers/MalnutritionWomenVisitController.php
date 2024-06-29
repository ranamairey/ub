<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\WomenTreatmentProgram;
use App\Models\MalnutritionChildVisit;
use App\Models\MalnutritionWomenVisit;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\MalnutritionWomenVisitRepositoryInterface;


#[\App\Aspects\transaction]
#[\App\Aspects\Logger]
class MalnutritionWomenVisitController extends Controller
{
    use ApiResponseTrait;

    
    private MalnutritionWomenVisitRepositoryInterface $visitRepository;

    public function __construct(MalnutritionWomenVisitRepositoryInterface $visitRepository) 
    {
        $this->visitRepository = $visitRepository;
    }

    public function index($programId)
    {
        $visits = MalnutritionWomenVisit::where('programs_id', $programId)->get();

        if (!$visits->count()) {
            return $this->notFound('No visits found for program ID: ' . $programId);
        }

        return $this->success($visits);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'programs_id' => 'required|exists:Women_treatment_programs,id',
            'muac' => 'required|numeric',
            'note' => 'string',
            'next_visit_date' => 'date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $program =WomenTreatmentProgram::find($request->programs_id);

        if(!$program){
            return $this->notFound($request->programs_id , "Program not found");
        }
        $employee_id = auth('sanctum')->user()->id;
        $employee_choise_id = EmployeeChoise::where('employee_id', $employee_id)->latest('created_at')->first()->id;
        $request->employee_id = $employee_id;
        $request->employee_choise_id =  $employee_choise_id;
        $visit = $this->visitRepository->createVisit($request);
        return $this->created($visit);
    }

}
