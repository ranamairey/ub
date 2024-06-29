<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface MedicalCenterMedicineRepositoryInterface
{
    public function createInventory(Request $request);
    
}
