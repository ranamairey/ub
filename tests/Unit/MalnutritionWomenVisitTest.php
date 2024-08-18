<!-- 

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MalnutritionWomenVisitTest extends TestCase
{
    
    public function testStoreSuccessful()
{
    $employee = Employee::factory()->create();
    $program = WomenTreatmentProgram::factory()->create();

    $this->actingAs($employee, 'sanctum');

    $request = [
        'programs_id' => $program->id,
        'muac' => 25.5,
        'note' => 'Patient is doing well',
        'next_visit_date' => '2024-09-01',
    ];

    $response = $this->post('/api/store', $request);

    $response->assertStatus(201);
    $this->assertDatabaseHas('visits', [
        'programs_id' => $program->id,
        'muac' => 25.5,
    ]);
}

public function testStoreValidationFailure()
{
    $employee = Employee::factory()->create();

    $this->actingAs($employee, 'sanctum');

    $request = [
        'programs_id' => 'invalid', // Invalid programs_id
        'muac' => 'invalid', // Invalid muac
        'note' => 123, // Invalid note
        'next_visit_date' => 'invalid-date', // Invalid date format
    ];

    $response = $this->post('/api/store', $request);

    $response->assertStatus(400);
    $response->assertJsonValidationErrors(['programs_id', 'muac', 'note', 'next_visit_date']);
}

public function testStoreUnauthorized()
{
    $request = [
        'programs_id' => 1,
        'muac' => 25.5,
        'note' => 'Patient is doing well',
        'next_visit_date' => '2024-09-01',
    ];

    $response = $this->post('/api/store', $request);

    $response->assertStatus(401);
    $response->assertJson(['message' => 'Unauthenticated.']);
}

public function testStoreNonExistentProgram()
{
    $employee = Employee::factory()->create();

    $this->actingAs($employee, 'sanctum');

    $request = [
        'programs_id' => 9999, // Non-existent program ID
        'muac' => 25.5,
        'note' => 'Patient is doing well',
        'next_visit_date' => '2024-09-01',
    ];

    $response = $this->post('/api/store', $request);

    $response->assertStatus(404);
    $response->assertJson(['message' => 'Program not found']);
}



} -->
