<?php

namespace App\Http\Controllers;

use App\Models\MedicalCenterMedicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponseTrait;
use App\Models\EmployeeChoise;
use Illuminate\Support\Facades\Auth;

class MedicalCenterMedicineController extends Controller
{
    use ApiResponseTrait;

    public function updateMedicineStock(Request $request)
    {
        $medicineQuantities = $request->get('medicine_quantities');


        $employee = Auth::guard('sanctum')->user()->with('employeeChoises')->first();

        if (!$employee) {
            return $this->unauthorized(null, 'Unauthorized access');
        }

        $chosenMedicalCenterId = $employee->employeeChoises->first()->medical_center_id;

        // Retrieve medical center ID from employee choice



        try {
            DB::transaction(function () use ($chosenMedicalCenterId, $medicineQuantities) {
                $updatedMedicines = [];

                foreach ($medicineQuantities as $medicineId => $quantity) {
                    $previousQuantity = DB::table('medical_center_medicines')
                        ->where('medical_center_id', $chosenMedicalCenterId)
                        ->where('medicine_id', $medicineId)
                        ->value('quntity');

                    $updatedQuantity = $previousQuantity + $quantity;

                    DB::table('medical_center_medicines')
                        ->updateOrInsert(
                            [
                                'medical_center_id' => $chosenMedicalCenterId,
                                'medicine_id' => $medicineId,
                            ],
                            [
                                'quntity' => $updatedQuantity,
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
}
