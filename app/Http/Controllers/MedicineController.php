<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\MedicalCenterMedicine;
use Illuminate\Support\Facades\Validator;


class MedicineController extends Controller

{
    use ApiResponseTrait;

    public function addMedicine(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255|in:Nutrition,Ordinary',
            'scientific_name' => 'required|string|max:255',
            'titer' => 'required|string',
            'code' => 'required|integer',
            'unit' => 'required|string',

        ]);
        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $employee = auth('sanctum')->user();
        if (!$employee) {
        return $this->unauthorized('You are not logged in');
        }


        $medicine = new Medicine;
        $medicine->name = $request->input('name');
        $medicine->type = $request->input('type');
        $medicine->scientific_name = $request->input('scientific_name');
        $medicine->titer = $request->input('titer');
        $medicine->code = $request->input('code');
        $medicine->unit = $request->input('unit');
        $medicine->employee_id =  $employee->id;
        $medicine->save();

        return $this->created($medicine);
    }

    public function getMedicineById($id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            // Handle case where medicine is not found (e.g., return error response)
            return response()->json(['message' => 'Medicine not found'], 404);
        }

        $employee = auth('sanctum')->user();
        $employee_choise = EmployeeChoise::where('employee_id', $employee->id)->latest('created_at')->first();

        if (!$employee_choise) {
            // Handle case where employee choice is not found (e.g., log error)
            \Log::error('Employee choice not found for user ID: ' . $employee->id);
            // Consider returning a generic response without medical center info
        }

        $medicalCenterId = $employee_choise ? $employee_choise->medical_center_id : null;

        $medicalCenterMedicine = MedicalCenterMedicine::where([
            ['medical_center_id', $medicalCenterId],
            ['medicine_id', $medicine->id]
        ])->first();

        if ($medicalCenterMedicine) {
            $medicine->quantity = $medicalCenterMedicine->quantity;
        } else {
            // Handle case where medical center medicine association is not found
            $medicine->quantity = 0; // Or set a default value based on your logic
        }

        return $medicine;
    }


    

    public function getAllmedicines(){

        $medicines = Medicine::all();

        if ($medicines->isEmpty()) {
            return $this->notFound([],'لايوجد دواء' );
        }

        return $this->success($medicines);
    }

    public function medicineDestruction(Request $request){
        $validator = Validator::make($request->all(), [
            'medical_center_medicine_id' =>  ['required', 'exists:medical_center_medicines,id'],
            'reason' => 'required|string|in:expiration,poorstorage,emergency',
            'quantity' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }
        $quantity = $request->input('quantity');
        $medicalCenterMedicineId = $request->input('medical_center_medicine_id');
        $medicalCenterMedicine =MedicalCenterMedicine::find($medicalCenterMedicineId);
        if(!$medicalCenterMedicine){
            return $this->notFound($medicalCenterMedicineId , "لا يوجد مركز موافق للمعرف المعطى");
        }
        if($quantity > $medicalCenterMedicine->quantity){
            return $this->error($quantity , "الكمية المطلوبة أكبر من الكمية المتوفرة");
        }
        $medicalCenterMedicine->quantity -= $quantity;
        $medicalCenterMedicine->save();

        return $this->success($medicalCenterMedicine);

    }
}
