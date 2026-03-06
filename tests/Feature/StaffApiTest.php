<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Staff;
// This allows us to use the RefreshDatabase trait in our tests
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffApiTest extends TestCase
{

    use RefreshDatabase;
   
    // checks if all staff members can be retrieved
    public function test_can_get_all_staff(): void
    {
        Staff::create([
            'name' => 'Test user',
            'role' => 'cook',
            'phone_number' => '1234567890',
        ]);

        $response = $this->getJson('/api/staff');

        $response->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment(['name' => 'Test user']);
    }

    // checks if a new staff member can be created
    public function test_can_create_staff(): void
    {
        $response = $this->postJson('/api/staff', [
            'name' => 'Jane Doe',
            'role' => 'cook',
            'phone_number' => '1234567890',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Jane Doe']);

        $this->assertDatabaseHas('staff', ['name' => 'Jane Doe']);
    }

    // checks for validation error: empty body fields not filled out
    public function test_create_staff_validates_required_fields(): void
    {
        $response = $this->postJson('/api/staff', []);


        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'role', 'phone_number']);
    }
    
    // checks for validation error: invalid role
    public function test_create_staff_validates_role_enum(): void
    {
        $response = $this->postJson('/api/staff', [
            'name' => 'Test',
            'role' => 'invalid_role',
            // tests 11 digit international phone number
            'phone_number' => '11234567890',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['role']);
    }
}
