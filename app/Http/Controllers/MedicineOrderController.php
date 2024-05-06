<?php

namespace App\Http\Controllers;

use App\Models\DoctorVisit;
use Illuminate\Http\Request;
use App\Models\MedicineOrder;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\MedicalCenterMedicine;
use Illuminate\Support\Facades\Validator;


class MedicineOrderController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MedicineOrder  $medicineOrder
     * @return \Illuminate\Http\Response
     */
    public function show(MedicineOrder $medicineOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MedicineOrder  $medicineOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicineOrder $medicineOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedicineOrder  $medicineOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicineOrder $medicineOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MedicineOrder  $medicineOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicineOrder $medicineOrder)
    {
        //
    }
}
