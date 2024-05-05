<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class MedicineController extends Controller

{
    use ApiResponseTrait;

    public function addMedicine(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'scientific_name' => 'required|string|max:255',
            'titer' => 'required|integer',
            'code' => 'required|integer',
            'unit' => 'required|integer',

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
}
