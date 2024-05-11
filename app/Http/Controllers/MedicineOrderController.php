<?php

namespace App\Http\Controllers;
use App\Models\DoctorVisit;
use Illuminate\Http\Request;
use App\Models\MedicineOrder;
use App\Traits\ApiResponseTrait;
use App\Models\RoutineChildVisit;
use App\Models\RoutineWomenVisit;
use App\Http\Controllers\Controller;
use App\Models\MedicalCenterMedicine;
use App\Models\MalnutritionChildVisit;
use App\Models\MalnutritionWomenVisit;
use Illuminate\Support\Facades\Validator;


class MedicineOrderController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllMedicineOrders()
    {
        $medicineOrders = MedicineOrder::all();

        if ($medicineOrders->isEmpty()) {
            return $this->notFound([],'No medicine order found' );
        }

        foreach ($medicineOrders as $medicineOrder) {
            $medicalCenterMedicine =$medicineOrder->medicalCenterMedicine;
            $medicine = $medicalCenterMedicine->medicine;
            $medicineOrder->medicine = $medicine;
            // $medicineOrders->makeHidden('medical_center_medicine');
        }

        return $this->success($medicineOrders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //  by boctor or nutrutionist
    public function doctorMedicineOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visit_id' => 'required|integer',
            'quantity' => 'required|integer',
            'activity_id' => ['required', 'exists:activities,id'],
            'medical_center_medicine_id' => ['required', 'exists:medical_center_medicines,id'],

        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $visit = DoctorVisit::find( $request->input('visit_id'));

        if(!$visit){
            return $this->notFound( $request->input('visit_id') , "Doctor visit with given id is not found");
        }

        $medicalCenterMedicine= MedicalCenterMedicine::find( $request->input('medical_center_medicine_id'));

        if(!$medicalCenterMedicine){
            return $this->notFound($request->input('medical_center_medicine_id') , "Medicine with given id is not found");
        }
        $employee = auth('sanctum')->user();


        $medicineOrder =$visit->medicineOrders()->create([
            'employee_id' => $employee->id,
            'quantity'=> $request->input('quantity'),
            'activity_id' => $request->input('activity_id'),
            'medical_center_medicine_id' => $medicalCenterMedicine->id,
            'is_aprroved' => false,
        ]);

        return $this->created($medicineOrder);
    }


    public function WomenNutritionistsMedicineOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visit_id' => 'required|integer',
            'quantity' => 'required|integer',
            'activity_id' => ['required', 'exists:activities,id'],
            'medical_center_medicine_id' => ['required', 'exists:medical_center_medicines,id'],
            // type Malnutrition or routine
            'type' => 'required|in:malnutrition,routine',

        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        if($request->input('type') == "malnutrition"){
            $visit = MalnutritionWomenVisit::find( $request->input('visit_id'));
            if(!$visit){
                return $this->notFound( $request->input('visit_id') , "Malnutrition Women Visitwith given id is not found");
            }
        }

        if($request->input('type') == "routine"){
            $visit = RoutineWomenVisit::find( $request->input('visit_id'));
            if(!$visit){
                return $this->notFound( $request->input('visit_id') , "Routine Women visit with given id is not found");
            }
        }

        $medicalCenterMedicine= MedicalCenterMedicine::find( $request->input('medical_center_medicine_id'));

        if(!$medicalCenterMedicine){
            return $this->notFound($request->input('medical_center_medicine_id') , "Medicine with given id is not found");
        }

        // check the quantity of the medicine before make the order
        // //////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////
        $employee = auth('sanctum')->user();

        $medicineOrder =$visit->medicineOrders()->create([
            'employee_id' => $employee->id,
            'quantity'=> $request->input('quantity'),
            'activity_id' => $request->input('activity_id'),
            'medical_center_medicine_id' => $medicalCenterMedicine->id,
            'is_aprroved' => false,
        ]);

        return $this->created($medicineOrder);
    }


    public function ChildNutritionistsMedicineOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visit_id' => 'required|integer',
            'quantity' => 'required|integer',
            'activity_id' => ['required', 'exists:activities,id'],
            'medical_center_medicine_id' => ['required', 'exists:medical_center_medicines,id'],
            // type Malnutrition or routine
            'type' => 'required|in:malnutrition,routine',

        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        if($request->input('type') == "malnutrition"){
            $visit = MalnutritionChildVisit::find( $request->input('visit_id'));
            if(!$visit){
                return $this->notFound( $request->input('visit_id') , "Malnutrition Child Visitwith given id is not found");
            }
        }

        if($request->input('type') == "routine"){
            $visit = RoutineChildVisit::find( $request->input('visit_id'));
            if(!$visit){
                return $this->notFound( $request->input('visit_id') , "Routine Child visit with given id is not found");
            }
        }

        $medicalCenterMedicine= MedicalCenterMedicine::find( $request->input('medical_center_medicine_id'));

        if(!$medicalCenterMedicine){
            return $this->notFound($request->input('medical_center_medicine_id') , "Medicine with given id is not found");
        }

        // check the quantity of the medicine before make the order
        // //////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////
        $employee = auth('sanctum')->user();

        $medicineOrder =$visit->medicineOrders()->create([
            'employee_id' => $employee->id,
            'quantity'=> $request->input('quantity'),
            'activity_id' => $request->input('activity_id'),
            'medical_center_medicine_id' => $medicalCenterMedicine->id,
            'is_aprroved' => false,
        ]);

        return $this->created($medicineOrder);
    }



}
