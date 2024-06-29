<?php

namespace App\Repositories;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Interfaces\MedicalCenterMedicineRepositoryInterface;



class MedicalCenterMedicineRepository implements MedicalCenterMedicineRepositoryInterface 
{
    public function createInventory(Request $request) 
    {
        $jsonData = $request->json()->all();
        $inventory = new Inventory([
            'data' => json_encode($jsonData, JSON_UNESCAPED_UNICODE),
            'old_data' =>json_encode($request->inventory_data, JSON_UNESCAPED_UNICODE)
        ]);
        return $inventory;
    }

    
}
