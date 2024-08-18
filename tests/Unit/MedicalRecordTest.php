<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;

use Tests\TestCase;
use App\Models\MedicalRecord;
use App\Models\User;


class MedicalRecordTest extends TestCase
{
    use WithoutMiddleware;
    
    
    public function testStoreValidationFailure()
    {
        $request = [
            'category' => 'child',
            'name' => 'John',
        
        ];

        $response = $this->post('/api/storeRecord', $request);
        $this->assertEquals(422 ,$response->getData()->code); 
    }

    public function testStoreGenderCategoryMismatch()
    {
        $request =[
            'category' => 'pregnant',
            'gender' => 'Male',
            'name' => 'John',
            'mother_name' => 'Jane',
            'father_name' => 'Doe',
            'last_name' => 'Smith',
            'phone_number' => '1234567890',
            'residence_status' => 'Resident',
            'special_needs' => false,
            'birth_date' => '2000-01-01',
            'address' => [
                'subdistrict_id' => 1,
                'name' => '123 Main St',
            ]];
        $response = $this->post('/api/storeRecord', $request);
        $this->assertEquals(400 ,$response->getData()->code); 
    }

    public function testStoreExistingMedicalRecord()
    {
        
        MedicalRecord::create([
            'employee_id' => 1,
            'category' => 'child',
            'gender' => 'Female',
            'name' => 'Noor',
            'mother_name' => 'Jane',
            'father_name' => 'Doe',
            'last_name' => 'Smith',
            'phone_number' => '1234567890',
            'residence_status' => 'Resident',
            'special_needs' => false,
            'birth_date' => '2000-01-01',
            'related_person_phone_number' => "098989898"
        ]);

        $request = [
            
            'name' => 'John',
            'mother_name' => 'Jane',
            'father_name' => 'Doe',
            'last_name' => 'Smith',
            'gender' => 'Male',
            'phone_number' => '1234567890',
            'residence_status' => 'Resident',
            'special_needs' => false,
            'birth_date' => '2000-01-01',
            'related_person_phone_number' => "098981298",
            'address' => [
                'subdistrict_id' => 1,
                'name' => '123 Main St',
            ],
        ];
        $response = $this->post('/api/storeRecord', $request);
        $this->assertEquals(422 ,$response->getData()->code); 
    }

    public function testStoreSuccessfulCreation()
    {
        $request1 = [
            "user_name" => "nutritionist",
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

        $request =  [
            'category' => 'pregnant',
            'name' => 'M4',
            'mother_name' => 'S4',
            'father_name' => 'A4',
            'last_name' => 'Sarhan4',
            'gender' => 'Female',
            'phone_number' => '00123404910',
            'residence_status' => 'Resident',
            'related_person_phone_number' => "009008928",
            'related_person' => "malaks",
            'special_needs' => true,
            'birth_date' => '2000-01-01',
            'address' => [
                'subdistrict_id' => 1,
                'name' => '123 Main St',
            ],
        ];
        
        $response = $this->withHeaders(["Authorization" => "Bearer " . $token])->
        post('/api/storeRecord', $request);
        $this->assertEquals(201 ,$response->json('code')); 
        
    }

}
