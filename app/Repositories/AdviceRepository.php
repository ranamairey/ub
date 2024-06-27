<?php

namespace App\Repositories;
use App\Models\Advice;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Interfaces\AdviceRepositoryInterface;


class AdviceRepository implements AdviceRepositoryInterface 
{
   public function createAdvice(Request $request){
    $advice = Advice::create([
        'employee_id' => $request->employee_id,
        'subject' => $request->input('subject'),
        'relative_activity' => $request->input('relative_activity'),
        'target_group' => $request->input('target_group'),
        'image' => $request->file('image') ? $request->file('image')->store('advice_images') : null,
    ]);
    return $advice;
   }
    
}
