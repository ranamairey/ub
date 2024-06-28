<?php

namespace App\Repositories;
use App\Models\Agency;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Interfaces\AppointmentRepositoryInterface;


class AppointmentRepository implements AppointmentRepositoryInterface 
{
    public function createAppointment(Request $request){
        $appointment = Appointment::create([
            'employee_id' => $request->employee_id,
            'receptionist_id' => $request->receptionist_id,
            'medical_record_id' => $request->medical_record_id,
            'employee_type' => $request->employee_type
        ]);
        return $appointment;
    }

    public function deleteAppointment ($id){
        $appointment = Appointment::find($id);
        $appointment->delete();
        return;
    }
   
    
}
