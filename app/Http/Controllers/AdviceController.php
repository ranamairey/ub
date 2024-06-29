<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Advice;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\AdviceRepositoryInterface;

class AdviceController extends Controller

{
    use ApiResponseTrait;

    private AdviceRepositoryInterface $adviceRepository;

    public function __construct(AdviceRepositoryInterface $adviceRepository) 
    {
        $this->adviceRepository = $adviceRepository;
    }

    public function createAdvice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|max:255',
            'relative_activity' => 'required|max:255',
            'target_group' => 'required|in:child,pregnant,both|max:255',
            'main_img_url' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->unprocessable($validator->errors());
        }



        $employee = auth('sanctum')->user();

        $request->employee_id =$employee->id; 
        $advice = $this->adviceRepository->createAdvice($request);
        


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
            $advices = Advice::where('target_group', 'child')->distinct()->get();
        } else  if($userInput==='pregnant'){

            $advices = Advice::whereIn('target_group', [$userInput, 'pregnant'])->distinct()->get();
            
        }
        else{

            $advices = Advice::whereIn('target_group', [$userInput, 'both'])->distinct()->get();
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
