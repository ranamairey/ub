<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\MedicalCenter;
use App\Models\EmployeeChoise;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MedicalCenterMedicine;

class MedicalCenterMedicineController extends Controller
{
    use ApiResponseTrait;

    public function updateMedicineStock(Request $request)
    {
        $medicineQuantities = $request->get('medicine_quantities');


        $employee = Auth::guard('sanctum')->user();

        if (!$employee) {
            return $this->unauthorized(null, 'Unauthorized access');
        }

        $chosenMedicalCenterId =  $employee->employeeChoises()->latest('created_at')->first()->medical_center_id;
        echo $chosenMedicalCenterId;

        // Retrieve medical center ID from employee choice



        try {
            DB::transaction(function () use ($chosenMedicalCenterId, $medicineQuantities) {
                $updatedMedicines = [];

                foreach ($medicineQuantities as $medicineId => $quantity) {
                    $previousQuantity = DB::table('medical_center_medicines')
                        ->where('medical_center_id', $chosenMedicalCenterId)
                        ->where('medicine_id', $medicineId)
                        ->value('quantity');

                    $updatedQuantity = $previousQuantity + $quantity;

                    DB::table('medical_center_medicines')
                        ->updateOrInsert(
                            [
                                'medical_center_id' => $chosenMedicalCenterId,
                                'medicine_id' => $medicineId,
                            ],
                            [
                                'quantity' => $updatedQuantity,
                            ]
                        );

                    $updatedMedicines[] = [
                        'medicine_id' => $medicineId,
                        'previous_quantity' => $previousQuantity,
                        'new_quantity' => $updatedQuantity,
                    ];
                }

            });

            return $this->success( 'Medicine stock updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating medicine stock: ' . $e->getMessage());
            return $this->error(null, 'Error updating medicine stock.');
        }

    }

    public function getMedicalCenterMedicine(){
        $employee = auth('sanctum')->user();
        $medicalCenterId =  $employee->employeeChoises()->latest('created_at')->first()->medical_center_id;
        $medicalCenter = MedicalCenter::find($medicalCenterId);
        $medicalCenterMedicines = $medicalCenter->medicalCenterMedicines;

        if (!$medicalCenterMedicines->count()) {
            return $this->notFound('No medicines found for this medical center');
        }
        
        foreach ($medicalCenterMedicines as $medicalCenterMedicine) {
            $medicalCenterMedicine->medicine=$medicalCenterMedicine->medicine;
        }
        return $this->success($medicalCenterMedicines);
    }

    public function getNotEmptyMedicalCenterMedicine(){
        $employee = auth('sanctum')->user();
        $medicalCenterId =  $employee->employeeChoises()->latest('created_at')->first()->medical_center_id;
        $medicalCenter = MedicalCenter::find($medicalCenterId);
        $medicalCenterMedicines = $medicalCenter->medicalCenterMedicines()->where('quantity', '!=' , 0)->get();


        if (!$medicalCenterMedicines->count()) {
            return $this->notFound('No medicines found for this medical center');
        }
        
        foreach ($medicalCenterMedicines as $medicalCenterMedicine) {
            if(!($medicalCenterMedicine->quantity == 0)){
                $medicalCenterMedicine->medicine=$medicalCenterMedicine->medicine;
            }
        }
        return $this->success($medicalCenterMedicines);
    }

    public function medicineInventory(Request $request){
            $jsonData = $request->json()->all();
            $newRecord = new Inventory;
            $newRecord->data = json_encode($jsonData); 
            $newRecord->save();
            return $this->success($newRecord , "تم حفظ نتيجة الجرد.");
           
    }
    
}
