<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponseTrait;
use App\Models\Advice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdviceController extends Controller

{
    use ApiResponseTrait;

    public function createAdvice(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'subject' => 'required|max:255|unique:advice,subject',
        'relative_activity' => 'required|max:255',
        'target_group' => 'required|in:child,pregnant|max:255',
      ]);

      if ($validator->fails()) {
        return $this->unprocessable($validator->errors());
      }

      $subject = $request->input('subject');
      $relativeActivity = $request->input('relative_activity');
      $targetGroup = $request->input('target_group');

      $employee = auth('sanctum')->user();

      $advice = Advice::create([
        'employee_id' => $employee->id,
        'subject' => $subject,
        'relative_activity' => $relativeActivity,
        'target_group' => $targetGroup,
      ]);

      return $this->success($advice);
    }



}
