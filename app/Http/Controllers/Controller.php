<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MedicineOrder;
use App\Models\ChildTreatmentProgram;
use App\Models\MalnutritionChildVisit;
// use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



//     public function test(){
//         // 
//         $writer = WriterEntityFactory::createXLSXWriter();
//         $desktop_path = getenv('HOME') . '/Desktop';
//         $writer->openToFile($desktop_path . '/tests.xlsx');
//         $row = WriterEntityFactory::createRowFromArray(["YOB السنة" , "Agency المنظمة" , "Field Office المكتب" , "Partner الشريك" , "Activity النشاط" ,"Location مدينة/قرية" , "Neighborhood"  
//         , "Site الموقع" , "Coverage Level مستوى التغطية" , "Address العنوان" , "Start Date من تاريخ" , "End Date إلى تاريخ" , "Reporting Month شهر التقرير" , "Modality طريقة الوصول" , "Beneficiaries Type نوع المستفيدين"
//         ,"Beneficiaries reached before? هل تم الوصول للمستفيدين من قبل؟" ,"With Disability? ذوي إحتياجات خاصة؟" ,"#Child|Male" , "#Child|Female" , "#Adult|Male" , "#Adult|Female" ,"#Child|Undefined Gender" 
//         , "#Undefined Age|Undefined Gender", "Total المجموع" , "Item إسم المادة", "Qty العدد" , "Unit" , "PCODE" , "Sub-Districts", "Districts", "Governorates"]);
// $writer->addRow($row);
// $writer->close();
// return true;

//     }

    
}
