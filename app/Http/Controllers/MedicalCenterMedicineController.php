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
use App\Interfaces\MedicalCenterMedicineRepositoryInterface;

class MedicalCenterMedicineController extends Controller
{
    use ApiResponseTrait;

    private MedicalCenterMedicineRepositoryInterface $medicalCenterMedicinesRepository;

    public function __construct(MedicalCenterMedicineRepositoryInterface $medicalCenterMedicinesRepository) 
    {
        $this->medicalCenterMedicinesRepository = $medicalCenterMedicinesRepository;
    }

    public function updateMedicineStock(Request $request)
    {
        $medicineQuantities = $request->get('medicine_quantities');


        $employee = Auth::guard('sanctum')->user();

        if (!$employee) {
            return $this->unauthorized(null, 'Unauthorized access');
        }

        $chosenMedicalCenterId =  $employee->employeeChoises()->latest('created_at')->first()->medical_center_id;
        echo $chosenMedicalCenterId;

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
            return $this->error(null, 'خطأ في التحديث');
        }

    }

    public function getMedicalCenterMedicine(){
        $employee = auth('sanctum')->user();
        $medicalCenterId =  $employee->employeeChoises()->latest('created_at')->first()->medical_center_id;
        $medicalCenter = MedicalCenter::find($medicalCenterId);
        $medicalCenterMedicines = $medicalCenter->medicalCenterMedicines;

        $medicalCenterMedicines = $medicalCenter->medicalCenterMedicines()->with('medicine')->whereHas('medicine', function ($query) {
            $query->where('type', 'Ordinary');
          })->get();

        if (!$medicalCenterMedicines->count()) {
            return $this->notFound('لا يوجد دواء في هذا المركز');
        }

        foreach ($medicalCenterMedicines as $medicalCenterMedicine) {
            $medicalCenterMedicine->medicine=$medicalCenterMedicine->medicine;
        }
        return $this->success($medicalCenterMedicines);
    }
    public function getMalnutritionMedicalCenterMedicine()
{
    $employee = auth('sanctum')->user();
    $medicalCenterId = $employee->employeeChoises()->latest('created_at')->first()->medical_center_id;
    $medicalCenter = MedicalCenter::find($medicalCenterId);
    $medicalCenterMedicines = $medicalCenter->medicalCenterMedicines()->with('medicine')->whereHas('medicine', function ($query) {
        $query->where('type', 'Nutrition');
      })->get();

    if (!$medicalCenterMedicines->count()) {
        return $this->notFound('No nutritional medicines found for this medical center');
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
        // $jsonData = $request->json()->all();
       
        // $newRecord->data = json_encode($jsonData, JSON_UNESCAPED_UNICODE);
        $inventoryData = MedicalCenterMedicine::join('medicines', 'medical_center_medicines.medicine_id', '=', 'medicines.id')
            ->select('medicines.name', 'medical_center_medicines.quantity')
            ->get()
            ->pluck('quantity', 'name')
            ->toArray();
        
        $request->inventory_data = $inventoryData;
        // $newRecord->old_data = json_encode($inventoryData, JSON_UNESCAPED_UNICODE);
        $newRecord = $this->medicalCenterMedicinesRepository->createInventory($request);
        
        // new Inventory([
        //     'data' => json_encode($jsonData, JSON_UNESCAPED_UNICODE),
        //     'old_data' =>json_encode($inventoryData, JSON_UNESCAPED_UNICODE)
        // ]);
        return $this->success($newRecord , "تم حفظ نتيجة الجرد.");
    }

}
