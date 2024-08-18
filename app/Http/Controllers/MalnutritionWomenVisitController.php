<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\WomenTreatmentProgram;
use App\Models\MalnutritionChildVisit;
use App\Models\MalnutritionWomenVisit;
use Illuminate\Support\Facades\Validator;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
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

    public function MalnutritionWomenVisitReport(Request $request){
        $userYear = $request->input('year');
        $userMonth= $request->input('month');
        
        $userDate = Carbon::create($userYear, $userMonth, 1);

        $writer = WriterEntityFactory::createXLSXWriter();
    
    
    $filename = "MalnutritionReport.xlsx";
    
    // Ad
    $timestamp = Carbon::now()->format('dmHi');
    $uniqueFilename = Str::beforeLast($filename, '.') . '_' . $timestamp . '.' . Str::afterLast($filename, '.');
    
    $filePath = "C:\\Users\\Asus\\Desktop\\" . $uniqueFilename;
    $writer->openToFile($filePath);
    
    $cells = [
        WriterEntityFactory::createCell('YearYOB السنة'),
        WriterEntityFactory::createCell('Agency المنظمة'),
        WriterEntityFactory::createCell('Office المكتب'),
        WriterEntityFactory::createCell('Partner الشريك'),
        WriterEntityFactory::createCell('Activity النشاط'),
        WriterEntityFactory::createCell('Location مدينة/قرية'),
        WriterEntityFactory::createCell('Neighborhood'),
        WriterEntityFactory::createCell('Site الموقع'),
        WriterEntityFactory::createCell('Coverage Level مستوى التغطية'),
        WriterEntityFactory::createCell('Address العنوان'),
        WriterEntityFactory::createCell('Start Date من تاريخ'),
        WriterEntityFactory::createCell('End Date إلى تاريخ'),
        WriterEntityFactory::createCell('Reporting Month شهر التقرير'),
        WriterEntityFactory::createCell('Modality طريقة الوصول'),
        WriterEntityFactory::createCell('Beneficiaries Type نوع المستفيدين'),
        WriterEntityFactory::createCell('Beneficiaries reached before? هل تم الوصول للمستفيدين من قبل؟'),
        WriterEntityFactory::createCell('With Disability? ذوي إحتياجات خاصة؟'),
        WriterEntityFactory::createCell('#Child|Male'),
        WriterEntityFactory::createCell('#Child|Female'),
        WriterEntityFactory::createCell('#Adult|Male'),
        WriterEntityFactory::createCell('#Adult|Female'),
        WriterEntityFactory::createCell('Type'),
        WriterEntityFactory::createCell('Total المجموع'),
        WriterEntityFactory::createCell('Item إسم المادة'),
        WriterEntityFactory::createCell('Qty العدد'),
        WriterEntityFactory::createCell('Unit'),
        WriterEntityFactory::createCell('PCODE'),
        WriterEntityFactory::createCell('Sub-Districts'),
        WriterEntityFactory::createCell('Districts'),
        WriterEntityFactory::createCell('Governorates'),
    ];
    
    /** add a row at a time */
    $singleRow = WriterEntityFactory::createRow($cells);
    $writer->addRow($singleRow);

    $results = DB::table('medicine_orders as mo')
    ->join('malnutrition_women_visits as mwv', 'mwv.id', '=', 'mo.medicine_orderable_id')
    ->join('women_treatment_programs as mtp', 'mtp.id', '=', 'mwv.programs_id')
    ->join('medical_records as mr', 'mr.id', '=', 'mtp.medical_record_id')
    ->join('employees as emp', 'mwv.employee_id', '=', 'emp.id')
    ->join('addresses as ad', function($join) {
        $join->on('ad.addressable_id', '=', 'emp.id')
             ->where('ad.addressable_type', 'LIKE', '%Employee%');
    })
    ->join('subdistricts as sub', 'sub.id', '=', 'ad.subdistrict_id')
    ->join('districts as dis', 'dis.id', '=', 'sub.district_id')
    ->join('governorates as gov', 'gov.id', '=', 'dis.governorate_id')
    ->join('employee_choises as ec', 'ec.id', '=', 'mwv.employee_choise_id')
    ->join('agencies as a', 'a.id', '=', 'ec.agency_id')
    ->join('offices as o', 'o.id', '=', 'ec.office_id')
    ->join('partners as p', 'p.id', '=', 'ec.partner_id')
    ->join('coverages as co', 'co.id', '=', 'ec.coverage_id')
    ->join('activities as act', 'act.id', '=', 'ec.activity_id')
    ->join('accesses as acc', 'acc.id', '=', 'ec.access_id')
    ->join('medical_centers as mc', 'mc.id', '=', 'ec.medical_center_id')
    ->join('medical_center_medicines as mcm', 'mo.medical_center_medicine_id', '=', 'mcm.id')
    ->join('medicines as m', 'm.id', '=', 'mcm.medicine_id')
    ->where('mo.is_aprroved', true)
    ->where('mo.medicine_orderable_type', 'LIKE', '%MalnutritionWomen%')
    
    ->select(
        'a.name as agency_name',
        'o.name as office_name',
        'mwv.current_date as visit_date',
        'p.name as partner_name',
        'co.name as coverage_name',
        'act.name as activity_name',
        'acc.name as accesse_name',
        'mc.name as medical_name',
        'm.name as medicine_name',
        'm.unit as unit',
        'm.code as code',
        'mo.quantity as quantity',
        'ad.name as address_name',
        'sub.name as subdistrict_name',
        'dis.name as district_name',
        'gov.name as gov_name',
        'mr.special_needs as special_needs',
        'mr.category as category',
        'mr.gender as gender'
    )
    ->get();
 
    foreach($results as $result){
        $child_male =0;
        $child_female =0 ;
        $pregnant =0;
        
        if($result->category == "pregnant"){
            $pregnant=1;
        }
        if($result->category == "child" && $result->gender == "Male"){
            $child_male = 1;
        }
        if($result->category == "child" && $result->gender == "Female"){
            $child_female = 1;
        }
        $today = Carbon::now();
        $currentYear = $today->year;
        $currentMonth = $today->month;
        $newCells = [
            WriterEntityFactory::createCell($currentYear),
            WriterEntityFactory::createCell($result->agency_name),
            WriterEntityFactory::createCell($result->office_name),
            WriterEntityFactory::createCell($result->partner_name),
            WriterEntityFactory::createCell($result->activity_name),
            WriterEntityFactory::createCell($result->district_name . " " . $result->subdistrict_name),
            WriterEntityFactory::createCell(''),
            WriterEntityFactory::createCell($result->medical_name),
            WriterEntityFactory::createCell($result->coverage_name),
            WriterEntityFactory::createCell($result->address_name),
            WriterEntityFactory::createCell($result->visit_date),
            WriterEntityFactory::createCell($result->visit_date),
            WriterEntityFactory::createCell($currentMonth),
            WriterEntityFactory::createCell($result->accesse_name),
            WriterEntityFactory::createCell('beneficiary_type'),
            WriterEntityFactory::createCell('is_beneficiaries'),
            WriterEntityFactory::createCell($result->special_needs),
            WriterEntityFactory::createCell($child_male),
            WriterEntityFactory::createCell($child_female),
            WriterEntityFactory::createCell(0),
            WriterEntityFactory::createCell($pregnant),
            WriterEntityFactory::createCell("tfsp"),
            WriterEntityFactory::createCell(1),
            WriterEntityFactory::createCell($result->medicine_name),
            WriterEntityFactory::createCell($result->quantity),
            WriterEntityFactory::createCell($result->unit),
            WriterEntityFactory::createCell($result->code),
            WriterEntityFactory::createCell($result->district_name),
            WriterEntityFactory::createCell($result->subdistrict_name),
            WriterEntityFactory::createCell($result->gov_name),
        ];
        $singleRow = WriterEntityFactory::createRow($newCells);
    $writer->addRow($singleRow);
    }
    
    
    $writer->close();
    }
}
