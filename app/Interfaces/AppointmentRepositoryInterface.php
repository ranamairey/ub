<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AppointmentRepositoryInterface 
{
    public function createAppointment(Request $request);
    
}
