<?php

namespace App\Http\Controllers;
use App\Traits\ApiResponseTrait;
use App\Models\Advice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdviceController extends Controller

{
    use ApiResponseTrait;

    public function createAdvice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|max:255|unique:advice,subject',
            'relative_activity' => 'required|max:255',
            'target_group' => 'required|in:child,pregnant,both|max:255',
            'main_img_url' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $subject = $request->input('subject');
        $relativeActivity = $request->input('relative_activity');
        $targetGroup = $request->input('target_group');
        $image = $request->file('image');


        $employee = auth('sanctum')->user();

        $advice = Advice::create([
            'employee_id' => $employee->id,
            'subject' => $subject,
            'relative_activity' => $relativeActivity,
            'target_group' => $targetGroup,
            'image' => $image ? $image->store('advice_images') : null,
        ]);

        return $this->success($advice);
    }

    public function showAdvicesByInput(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_input' => 'required|string|in:child,pregnant,both',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }

        $userInput = Str::lower($request->input('user_input'));

        if ($userInput === 'child') {
            $advices = Advice::where('target_group', 'child')->get();
        } else  if($userInput==='pregnant'){

            $advices = Advice::whereIn('target_group', [$userInput, 'pregnant'])->get();
        }
        else{

            $advices = Advice::whereIn('target_group', [$userInput, 'both'])->get();
        }
        if ($advices->isEmpty()) {

            return $this->error('No advice found for this target group.');
        }




        return $this->success($advices);
    }
    public function adviceById (Request $request)
    {
        $input = $request->input('id');

        $query = Advice::where('id', $input)->findOrFail($input);

        if (is_null($query)) {
            return $this->notFound('النصيحة غير موجودة');
        }

        return $this->success($query, 'Advice retrieved successfully!');

    }





}
