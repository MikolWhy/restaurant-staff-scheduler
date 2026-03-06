<?php

namespace Tests\Feature;

use App\Models\Shift;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShiftApiTest extends TestCase
{
    use RefreshDatabase;

    // Add one shift via the DB, then hit GET /shifts and check we get it back
    public function test_can_list_all_shifts(): void
    {
        $staff = Staff::create([
            'name' => 'Test User',
            'role' => 'server',
            'phone_number' => '1234567890',
        ]);

        Shift::create([
            'staff_id' => $staff->id,
            'day' => '2026-03-10',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'role' => 'server',
        ]);

        $response = $this->getJson('/api/shifts');

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['day' => '2026-03-10']);
    }

    public function test_shifts_include_staff_relationship(): void
    {
        $staff = Staff::create([
            'name' => 'Alice',
            'role' => 'server',
            'phone_number' => '1234567890',
        ]);


        // Fixed date is fine here: we're only creating data in the DB, not sending it through the API, so validation never runs
        Shift::create([
            'staff_id' => $staff->id,
            'day' => '2026-03-10',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'role' => 'server',
        ]);

        $response = $this->getJson('/api/shifts');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Alice']);
    }


    // use future date here because this test POSTs to the API and validation requires day to be today or later
    public function test_can_create_shift(): void
    {
        $day = now()->addDays(2)->format('Y-m-d');

        $response = $this->postJson('/api/shifts', [
            'day' => $day,
            'start_time' => '10:00',
            'end_time' => '18:00',
            'role' => 'cook',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['role' => 'cook']);

        $this->assertDatabaseHas('shifts', ['day' => $day]);
    }

    // PATCH to assign a staff member to an unassigned shift, response should include that staff
    public function test_can_assign_staff_to_shift(): void
    {
        $staff = Staff::create([
            'name' => 'Bob',
            'role' => 'cook',
            'phone_number' => '11234567890',
        ]);

        $shift = Shift::create([
            'staff_id' => null,
            'day' => '2026-03-10',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'role' => 'cook',
        ]);

        $response = $this->patchJson("/api/shifts/{$shift->id}", [
            'staff_id' => $staff->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Bob']);

        $this->assertDatabaseHas('shifts', [
            'id' => $shift->id,
            'staff_id' => $staff->id,
        ]);
    }

    // Empty body should fail validation on required fields (day, start_time, end_time, role; staff_id is optional)
    public function test_create_shift_validates_required_fields(): void
    {
        $response = $this->postJson('/api/shifts', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['day', 'start_time', 'end_time', 'role']);
    }

    // End time before start time (same day) should be rejected. We use $day so the only error is end_time, not the date
    public function test_create_shift_validates_end_time_after_start(): void
    {
        $day = now()->addDays(2)->format('Y-m-d');

        $response = $this->postJson('/api/shifts', [
            'day' => $day,
            'start_time' => '18:00',
            'end_time' => '10:00',
            'role' => 'server',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['end_time']);
    }
}