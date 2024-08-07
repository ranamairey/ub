<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\DoctorVisit;
use Illuminate\Support\Str;
// use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use App\Models\MedicineOrder;
use Box\Spout\Common\Entity\Row;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\ChildTreatmentProgram;
use App\Models\HealthEducationLecture;
use App\Models\MalnutritionChildVisit;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;




class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

   public function toto(){

$writer = WriterEntityFactory::createXLSXWriter();


$filename = "ClinicsReport.xlsx";

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

$doctorVisits = DoctorVisit::all();

foreach($doctorVisits as $doctorVisit){
    $employee_choise = $doctorVisit->employeeChoise;
    $employee =  $doctorVisit->employee;
    $today = Carbon::now();
    $currentYear = $today->year;
    $currentMonth = $today->month;
    $newCells = [
        WriterEntityFactory::createCell($currentYear),
        WriterEntityFactory::createCell($employee_choise->agency->name),
        WriterEntityFactory::createCell($employee_choise->office->name),
        WriterEntityFactory::createCell($employee_choise->partner->name),
        WriterEntityFactory::createCell($employee_choise->activity->name),
        WriterEntityFactory::createCell($employee->addresses->first()->district->name . " " . $employee->addresses->first()->subdistrict->name),
        WriterEntityFactory::createCell(''),
        WriterEntityFactory::createCell($employee_choise->medicalCenter->name),
        WriterEntityFactory::createCell($employee_choise->coverage->name),
        WriterEntityFactory::createCell($employee->addresses->first()->name),
        WriterEntityFactory::createCell($doctorVisit->created_at->toDateString()),
        WriterEntityFactory::createCell($doctorVisit->date),
        WriterEntityFactory::createCell($currentMonth),
        WriterEntityFactory::createCell($employee_choise->access->name),
        WriterEntityFactory::createCell('beneficiary_type'),
        WriterEntityFactory::createCell('is_beneficiaries'),
        WriterEntityFactory::createCell('has_special_needs'),
        WriterEntityFactory::createCell('male_children_number'),
        WriterEntityFactory::createCell('female_children_number'),
        WriterEntityFactory::createCell('adult_men_number'),
        WriterEntityFactory::createCell('adult_women_number'),
        WriterEntityFactory::createCell('total'),
        WriterEntityFactory::createCell(''),
        WriterEntityFactory::createCell(''),
        WriterEntityFactory::createCell('0'),
        WriterEntityFactory::createCell('PCODE'),
        WriterEntityFactory::createCell($employee->addresses->first()->district->name),
        WriterEntityFactory::createCell($employee->addresses->first()->subdistrict->name),
        WriterEntityFactory::createCell($employee->addresses->first()->Governorate->name),
    ];
}
$singleRow = WriterEntityFactory::createRow($newCells);
$writer->addRow($singleRow);

$writer->close();



}


  }
