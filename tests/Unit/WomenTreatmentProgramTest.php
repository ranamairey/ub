<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\MedicalRecord;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class WomenTreatmentProgramTest extends TestCase
{
    use WithoutMiddleware;
    
    public function testCreateWomenTreatmentProgramSuccessful()
{
    
    $request1 = [
        "user_name" => "women-nutritionist",
        "password"=> "secret123",
        "employee_choise"=> [
            "medical_center_id"=> 1,
            "coverage_id"=> 1,
            "office_id"=>1,
            "activity_id"=> 1,
            "agency_id"=>1,
            "access_id"=>1,
            "partner_id"=> 1
        
            ]
    ];

    $response1 = $this->post('/api/login' , $request1);
    $token = $response1->getData()->data->token;


    $request = [
        'medical_record_id' => 3,
        'acceptance_reason' => 'Reason',
        'acceptance_type' => 'new',
        'target_weight' => 60.5,
        'tetanus_date' => '2024-08-15',
        'vitamin_a_date' => '2024-08-15',
        'end_date' => '2024-08-15',
        'end_cause' => 'Cause',
    ];

    $response = $this->withHeaders(["Authorization" => "Bearer " . $token])
    ->post('/api/createWomenTreatmentProgram', $request);

    $this->assertEquals(201 ,$response->getData()->code); 
    
}

public function testCreateWomenTreatmentProgramValidationFailure()
{
    
    $request = [
        'medical_record_id' => 1, 
        
    ];

    $response = $this->post('/api/createWomenTreatmentProgram', $request);

    $this->assertEquals(422 ,$response->json('code')); 
}

public function testCreateWomenTreatmentProgramUnauthorized()
{
    $request = [
        'medical_record_id' => 3,
        'acceptance_reason' => 'Reason',
        'acceptance_type' => 'new',
        'target_weight' => 60.5,
    ];

    $response = $this->post('/api/createWomenTreatmentProgram', $request);

    $this->assertEquals(401 ,$response->json('code')); 
}

public function testCreateWomenTreatmentProgramNonExistentMedicalRecord()
{
    $request1 = [
        "user_name" => "women-nutritionist",
        "password"=> "secret123",
        "employee_choise"=> [
            "medical_center_id"=> 1,
            "coverage_id"=> 1,
            "office_id"=>1,
            "activity_id"=> 1,
            "agency_id"=>1,
            "access_id"=>1,
            "partner_id"=> 1
        
            ]
    ];

    $response1 = $this->post('/api/login' , $request1);
    $token = $response1->getData()->data->token;


    $request = [
       
        'medical_record_id' => 999999,
        'acceptance_reason' => 'Reason',
        'acceptance_type' => 'new',
        'target_weight' => 60.5,
        'tetanus_date' => '2024-08-15',
        'vitamin_a_date' => '2024-08-15',
        'end_date' => '2024-08-15',
        'end_cause' => 'Cause',
    ];

    $response = $this->withHeaders(["Authorization" => "Bearer " . $token])->post('/api/createWomenTreatmentProgram', $request);

    $this->assertEquals(422 ,$response->getData()->code); 
}

}
