<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\MedicineOrder;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\ChildTreatmentProgram;
// use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use App\Models\MalnutritionChildVisit;
use OpenSpout\Common\Entity\Style\Style;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;




class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

   public function toto(){
 
$employees = Employee::all();

return (new FastExcel($employees))
    ->export('users.xlsx');



   }


  }
