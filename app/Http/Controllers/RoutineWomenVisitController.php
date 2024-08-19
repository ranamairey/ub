<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\EmployeeChoise;
use Illuminate\Validation\Rule;
use App\Traits\ApiResponseTrait;
use App\Models\RoutineWomenVisit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;


#[\App\Aspects\transaction]
#[\App\Aspects\Logger]
class RoutineWomenVisitController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($record)
    {
        $visits = RoutineWomenVisit::where('medical_record_id', $record)->get();

        if (!$visits->count()) {
            return $this->notFound('No visits found for Record ID: ' . $record);
        }

        return $this->success($visits);
    }


    public function createWomenVisit(Request $request){

        $validator = Validator::make($request->all(), [
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
            'current_status' => [
                'required',
                Rule::in(['mam','normal']),
            ],
            'status_type' => [
                'required',
                Rule::in(['pregnant','lactating','non']),
            ],
            //'date' => ['required', 'date'],
            'IYCF' => ['required', 'boolean'],
            'nutritional_survey' => ['required', 'boolean'],
            'micronutrients' => ['required', 'boolean'],
            'high_energy_biscuits' => ['required', 'boolean'],
            'health_education' => ['required', 'boolean'],
            'weight' => ['required','numeric'],


            ]);
        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }


        if (! MedicalRecord::where('id', $request->input('medical_record_id'))->exists()) {
            return $this->unprocessable($routineWomenVisit , 'The specified medical record does not exist.');
        }


        $employee = auth('sanctum')->user();
        $employee_id = auth('sanctum')->user()->id;
        $routineWomenVisit = RoutineWomenVisit::create([
            'employee_id' => $employee->id,
            'employee_choise_id' => EmployeeChoise::where('employee_id', $employee_id)->latest('created_at')->first()->id,
            'medical_record_id' => $request->input('medical_record_id'),
            'current_status' =>  $request->input('current_status'),
            'status_type'  =>   $request->input('status_type'),
            'date' => now()->format('Y-m-d'),
            'IYCF' => $request->input('IYCF'),
            'nutritional_survey' => $request->input('nutritional_survey'),
            'micronutrients' => $request->input('micronutrients'),
            'high_energy_biscuits' => $request->input('high_energy_biscuits'),
            'health_education' => $request->input('health_education'),
            'weight' => $request->input('weight'),
        ]);
        return $this->created($routineWomenVisit);



    }

    public function RoutineWomenVisitReport(Request $request){
        $userYear = $request->input('year');
        $userMonth= $request->input('month');
        
        $userDate = Carbon::create($userYear, $userMonth, 1);

        $writer = WriterEntityFactory::createXLSXWriter();
    
    
        $filename = "RoutineWomenVisitsReport.xlsx";
        
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
        ->join('routine_women_visits as mwv', 'mwv.id', '=', 'mo.medicine_orderable_id')
        ->join('medical_records as mr', 'mr.id', '=', 'mwv.medical_record_id')
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
        ->select(
            'a.name as agency_name',
            'o.name as office_name',
            'mwv.date as to_visit_date',
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
            'mr.gender as gender',
            'mwv.created_at as visit_date',
        )
        ->where('mo.is_aprroved', true)
        ->where('mo.medicine_orderable_type', 'LIKE', '%RoutineWomen%')
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
                WriterEntityFactory::createCell($result->to_visit_date),
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
