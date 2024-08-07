<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\HealthEducationLecture;
use Illuminate\Support\Facades\Validator;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use App\Interfaces\HealthEducationLectureRepositoryInterface;


class HealthEducationLectureController extends Controller
{
    use ApiResponseTrait;

    private HealthEducationLectureRepositoryInterface $lectureRepository;

    public function __construct(HealthEducationLectureRepositoryInterface $lectureRepository) 
    {
        $this->lectureRepository = $lectureRepository;
    }

    public function createLecture(Request $request)
    {
        $rules = [
            'male_children_number' => 'required|integer|min:0',
            'female_children_number' => 'required|integer|min:0',
            'adult_men_number' => 'required|integer|min:0',
            'adult_women_number' => 'required|integer|min:0',
            'total' => 'required|integer|min:0',
            'is_beneficiaries' => 'required|boolean',
            'beneficiary_type' => 'required|string',
            'material_name' => 'required|string',
            'program' => 'required|string',
            'program_category' => 'required|string',
            'date' => 'required|date',
            'partner_id' => ['required', 'integer' , 'exists:partners,id'],
            'access_id' => ['required', 'integer' , 'exists:accesses,id'],
            'agency_id' => ['required', 'integer' , 'exists:agencies,id'],
            'activity_id' => ['required', 'integer' , 'exists:activities,id'],
            'office_id' => ['required', 'integer' , 'exists:offices,id'],
            'coverage_id' => ['required', 'integer' , 'exists:coverages,id'],
            'address.subdistrict_id' => ['required', 'exists:subdistricts,id'],
            'address.name' => ['required', 'string', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $employee = auth('sanctum')->user();

        $request->employee_id =$employee->id;
        $healthEducationLecture = $this->lectureRepository->createLecture($request);

        $addressData = $request->get('address');


         $healthEducationLecture->addresses()->create([
            'name' => $addressData['name'],
            'subdistrict_id' => $addressData['subdistrict_id'],
        ]);

        $healthEducationLectureId = $healthEducationLecture->id;

        return $this->created($healthEducationLecture);
    }

    public function createReport(Request $request){

        $userYear = $request->input('year');
        $userMonth= $request->input('month');
        
        $userDate = Carbon::create($userYear, $userMonth, 1);

        
        $healthEducationLectures = HealthEducationLecture::whereYear('created_at', $userYear)
        ->whereMonth('created_at', $userMonth)
        ->get();


        $writer = WriterEntityFactory::createXLSXWriter();



        $filename = "HealthEducationReport.xlsx";

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
            WriterEntityFactory::createCell('Sub-Districts'),
            WriterEntityFactory::createCell('Districts'),
            WriterEntityFactory::createCell('Governorates'),
        ];

        $singleRow = WriterEntityFactory::createRow($cells);
        $writer->addRow($singleRow);


        $healthEducationLectures = HealthEducationLecture::all();
        foreach($healthEducationLectures as $healthEducationLecture){
            $employee =  $healthEducationLecture->employee;
            $employeeChoise =EmployeeChoise::where('employee_id', $employee->id)->latest('created_at')->first();

            $today = Carbon::now();
            $currentYear = $today->year;
            $currentMonth = $today->month;
            $newCells = [
                WriterEntityFactory::createCell($currentYear),
                WriterEntityFactory::createCell($healthEducationLecture->agency->name),
                WriterEntityFactory::createCell($healthEducationLecture->office->name),
                WriterEntityFactory::createCell($healthEducationLecture->partner->name),
                WriterEntityFactory::createCell($healthEducationLecture->activity->name),
                WriterEntityFactory::createCell($healthEducationLecture->addresses->first()->district->name . " " . $healthEducationLecture->addresses->first()->subdistrict->name),
                WriterEntityFactory::createCell(''),
                WriterEntityFactory::createCell($employeeChoise->medicalCenter->name),
                WriterEntityFactory::createCell($healthEducationLecture->coverage->name),
                WriterEntityFactory::createCell($healthEducationLecture->addresses->first()->name),
                WriterEntityFactory::createCell($healthEducationLecture->created_at->toDateString()),
                WriterEntityFactory::createCell($healthEducationLecture->date),
                WriterEntityFactory::createCell($currentMonth),
                WriterEntityFactory::createCell($healthEducationLecture->access->name),
                WriterEntityFactory::createCell($healthEducationLecture->beneficiary_type),
                WriterEntityFactory::createCell($healthEducationLecture->is_beneficiaries),
                WriterEntityFactory::createCell($healthEducationLecture->has_special_needs),
                WriterEntityFactory::createCell($healthEducationLecture->male_children_number),
                WriterEntityFactory::createCell($healthEducationLecture->female_children_number),
                WriterEntityFactory::createCell($healthEducationLecture->adult_men_number),
                WriterEntityFactory::createCell($healthEducationLecture->adult_women_number),
                WriterEntityFactory::createCell($healthEducationLecture->total),
                WriterEntityFactory::createCell($healthEducationLecture->addresses->first()->district->name),
                WriterEntityFactory::createCell($healthEducationLecture->addresses->first()->subdistrict->name),
                WriterEntityFactory::createCell($healthEducationLecture->addresses->first()->Governorate->name),
            ];
        }
        $singleRow = WriterEntityFactory::createRow($newCells);
        $writer->addRow($singleRow);

        $writer->close();
    }
}
